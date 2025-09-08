<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @php
        // Defaults orientados a HZ Band (todo sobre-escribible desde el controlador/componente)
        $artistName   = $artistName   ?? 'HZ Band';
        $brandTagline = $brandTagline ?? 'Canciones que suenan, emociones que sanan';
        $artistUrl    = $artistUrl    ?? 'https://open.spotify.com/artist/5VjCPceVjBTwlzewmaNAvd';
        $primaryColor = $primaryColor ?? '#10b981';
        $ogImage      = $ogImage      ?? null;
        $message      = $message      ?? '';

        // Prepara etiqueta opcional de OG:image sin usar @if/@endif
        $ogImageTag = '';
        if (!empty($ogImage)) {
            $ogImageEsc = e($ogImage);
            $ogImageTag = "<meta property=\"og:image\" content=\"{$ogImageEsc}\">";
        }

        // JSON-LD armado como arreglo y convertido a JSON (sin directivas Blade dentro)
        $ld = [
            '@context'    => 'https://schema.org',
            '@type'       => 'MusicGroup',
            'name'        => $artistName,
            'url'         => $artistUrl,
            'description' => $brandTagline,
            'genre'       => ['Pop', 'Rock', 'Alternativo'],
        ];
        if (!empty($ogImage)) {
            $ld['image'] = $ogImage;
        }
        $ldJson = json_encode($ld, JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
    @endphp

    <title>{{ $artistName }} — {{$brandTagline}}</title>

    <!-- Open Graph / Social -->
    <meta property="og:title" content="{{ $artistName }} — Música original, bilingüe y con buen groove">
    <meta property="og:description" content="{{ $brandTagline }}">
    <meta property="og:type" content="music.musician">
    <meta property="og:url" content="{{ $artistUrl }}">
    {!! $ogImageTag !!}
    <meta name="twitter:card" content="summary_large_image">

    <!-- JSON-LD para SEO musical -->
    <script type="application/ld+json">{!! $ldJson !!}</script>

    <!-- Tailwind (CDN) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        :root { --brand: {{ $primaryColor }}; }
        body {
            font-family: ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Ubuntu, Cantarell,
            Noto Sans, Helvetica, Arial, "Apple Color Emoji", "Segoe UI Emoji";
        }
        .brand-gradient {
            background-image: linear-gradient(90deg, var(--brand), color-mix(in oklab, var(--brand), white 25%));
        }
    </style>
</head>
<body class="bg-slate-950 text-slate-100">
<x-partials.nav artist-name="{{ $artistName }}" brand-tagline="{{$brandTagline}}" />

{{ $slot }}

<x-partials.footer artist-name="{{ $artistName }}" />
</body>
</html>
