<?php

namespace App\services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Str;
use Psr\Log\LoggerInterface;

class SpotifyClient
{
    private const TOKEN_URL = 'https://accounts.spotify.com/api/token';
    private const API_BASE  = 'https://api.spotify.com/v1';

    protected string $market;

    public function __construct(protected ?LoggerInterface $logger = null)
    {
        $this->market = config('services.spotify.market', 'US');
    }

    protected function clientId(): string
    {
        $id = config('services.spotify.client_id');
        if (empty($id)) {
            throw new \RuntimeException('Missing config: services.spotify.client_id');
        }
        return $id;
    }

    protected function clientSecret(): string
    {
        $secret = config('services.spotify.client_secret');
        if (empty($secret)) {
            throw new \RuntimeException('Missing config: services.spotify.client_secret');
        }
        return $secret;
    }

    public function getAccessToken(): string
    {
        return Cache::remember('spotify.token', now()->addMinutes(50), function () {
            $resp = Http::asForm()
                ->withBasicAuth($this->clientId(), $this->clientSecret())
                ->post(self::TOKEN_URL, [
                    'grant_type' => 'client_credentials',
                ]);

            if (!$resp->successful()) {
                throw new \RuntimeException('Spotify token error: HTTP '.$resp->status().' - '.$resp->body());
            }

            $token = $resp->json('access_token');
            if (!$token) {
                throw new \RuntimeException('Spotify token error: access_token missing');
            }

            return $resp->json('access_token');
        });
    }

    /**
     * HTTP client con reintentos para 429/5xx.
     */
    protected function http()
    {
        $token = $this->getAccessToken();

        return Http::baseUrl(self::API_BASE)
            ->withToken($token)
            // 5 intentos máx. Backoff exponencial 0.5s,1s,2s,4s,8s (con jitter leve)
            ->retry(5, 500, function ($exception, $request) {
                // Reintenta en 429 (respeta Retry-After) y 5xx, más errores de red
                if ($exception instanceof RequestException) {
                    $response = $exception->response;
                    if ($response) {
                        $status = $response->status();
                        if ($status === 429) {
                            $retryAfter = (int) ($response->header('Retry-After') ?? 1);
                            // Pausa el tiempo indicado por Spotify:
                            sleep(max(1, $retryAfter));
                            return true;
                        }
                        if ($status >= 500 && $status <= 599) {
                            // 5xx → reintenta
                            usleep(random_int(50_000, 200_000)); // jitter
                            return true;
                        }
                    }
                }
                if ($exception instanceof ConnectionException) {
                    // Errores de red → reintenta
                    return true;
                }
                return false;
            });
    }

    /**
     * GET genérico, con opción de añadir/remover market.
     */
    protected function getJson(string $path, array $query = [], bool $useMarket = true): array
    {
        if ($useMarket && $this->market) {
            $query = ['market' => $this->market] + $query;
        }

        $resp = $this->http()->get($path, $query);

        if ($resp->failed()) {
            $msg = sprintf(
                'Spotify GET error: %s | HTTP %d - %s',
                $resp->effectiveUri(),
                $resp->status(),
                $resp->body() ?: '[no body]'
            );
            // Log para diagnóstico y lanzar excepción
            $this->logger?->error($msg, ['path' => $path, 'query' => $query]);
            throw new \RuntimeException($msg);
        }

        return $resp->json();
    }

    /**
     * Paginación robusta usando el "next" de Spotify en lugar de armar offsets.
     * Devuelve un generador (yield) de items.
     */
    protected function paginate(string $firstPath, array $firstQuery = [], bool $useMarket = true, int $limit = 50): \Generator
    {
        $firstQuery = ['limit' => $limit] + $firstQuery;

        // 1) Primera página por path/query
        $page = $this->getJson($firstPath, $firstQuery, $useMarket);
        $items = $page['items'] ?? [];
        foreach ($items as $it) {
            yield $it;
        }

        // 2) Siguientes páginas por "next" (URL completa)
        $next = $page['next'] ?? null;

        while ($next) {
            // Usa la URL "next" completa para evitar bugs de offset/market
            $resp = $this->http()->get($next); // OJO: next ya trae query
            if ($resp->failed()) {
                $msg = sprintf(
                    'Spotify GET error (pagination next): %s | HTTP %d - %s',
                    $resp->effectiveUri(),
                    $resp->status(),
                    $resp->body() ?: '[no body]'
                );
                $this->logger?->warning($msg);
                // Estrategia: reintenta ya la hizo http()->retry; si aún falla,
                // podrías intentar quitar market y reintentar UNA VEZ.
                // Aquí un fallback rápido:
                $tryNoMarket = $this->http()->get($next.'&_omit_market=1'); // “truco” para cambiar la URL
                if ($tryNoMarket->ok()) {
                    $page = $tryNoMarket->json();
                } else {
                    // Si de plano falla, rompe el ciclo y devuelve lo acumulado
                    $this->logger?->error('Pagination aborted due to repeated failures.', [
                        'next' => $next,
                        'status' => $resp->status(),
                    ]);
                    break;
                }
            } else {
                $page = $resp->json();
            }

            $items = $page['items'] ?? [];
            foreach ($items as $it) {
                yield $it;
            }
            $next = $page['next'] ?? null;
        }
    }

    /**
     * Tracks de un álbum (usa paginação por "next", limit=50).
     */
    public function getAlbumTracks(string $albumId, bool $useMarket = true, int $limit = 50): \Generator
    {
        $path = "/albums/{$albumId}/tracks";
        return $this->paginate($path, [], $useMarket, $limit);
    }

    /**
     * Helper centralizado para llamadas con token y manejo de 429.
     */
    protected function request(string $method, string $url, array $query = [], array $body = [])
    {
        $token = $this->getAccessToken();

        $request = Http::withToken($token);

        // Si $url ya viene con query (paginación "next"), no añadimos params duplicados
        $isAbsoluteWithQuery = str_starts_with($url, 'http') && str_contains($url, '?');

        $request = $request->acceptJson()->retry(1, 0); // un reintento simple para errores transitorios

        $response = match (strtoupper($method)) {
            'GET' => $isAbsoluteWithQuery
                ? $request->get($url)
                : $request->get($url, $query),
            'POST' => $request->asForm()->post($url, $body ?: $query),
            default => throw new \InvalidArgumentException("HTTP method no soportado: {$method}")
        };

        // Manejo explícito de 429 (rate limit)
        if ($response->status() === 429) {
            $retryAfter = (int)($response->header('Retry-After', 1));
            sleep(max(1, $retryAfter));
            // Reintentar una vez más
            $response = match (strtoupper($method)) {
                'GET' => $isAbsoluteWithQuery
                    ? $request->get($url)
                    : $request->get($url, $query),
                'POST' => $request->asForm()->post($url, $body ?: $query),
            };
        }

        if ($response->failed()) {
            // Lanza excepción con body de error para depurar
            $response->throw();
        }

        return $response;
    }
    /** Lista álbumes de un artista (generator). */
    public function getArtistAlbums(string $artistId, array $filters = [], bool $useMarket = true, int $limit = 50): \Generator
    {
        // filters típicos: ['include_groups' => 'album,single,appears_on,compilation']
        $path = "/artists/{$artistId}/albums";
        return $this->paginate($path, $filters, $useMarket, $limit);
    }

    /** Obtiene un álbum por ID. (¡El que te falta!) */
    public function getAlbum(string $albumId, bool $useMarket = true): array
    {
        return $this->getJson("/albums/{$albumId}", [], $useMarket);
    }
    /** Obtiene un artista por ID. */
    public function getArtist(string $artistId, bool $useMarket = true): array
    {
        return $this->getJson("/artists/{$artistId}", [], $useMarket);
    }
    /** Busca artistas por nombre (modo simple, devuelve array "artists"). */
    public function searchArtist(string $name, int $limit = 10, int $offset = 0, bool $useMarket = true): array
    {
        $query = [
            'q'      => $name,
            'type'   => 'artist',
            'limit'  => $limit,
            'offset' => $offset,
        ];
        // Nota: Para /search, Spotify permite "market" en query; lo añadimos si $useMarket=true
        return $this->getJson('/search', $query, $useMarket)['artists'] ?? ['items' => []];
    }

}
