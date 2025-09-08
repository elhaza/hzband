@props([
  'artistName' => 'HZ Band',
  'artistId'   => '5VjCPceVjBTwlzewmaNAvd',
  'brandTagline' => 'Letras originales de Hazael Gomez'
])

<section class="relative isolate overflow-hidden">
    <!-- background -->
    <div class="absolute inset-0 -z-10 bg-gradient-to-b from-slate-900 via-slate-950 to-black"></div>
    <div class="absolute -top-32 inset-x-0 -z-10 blur-3xl opacity-30"
         style="background: radial-gradient(70rem 30rem at 50% -10%, var(--brand) 0%, transparent 60%);">
    </div>

    <div class="mx-auto max-w-7xl px-6 pt-16 pb-16 md:pt-24 md:pb-24">
        <div class="grid items-center gap-10 md:grid-cols-2">
            <div>
        <span class="inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/5 px-3 py-1 text-xs uppercase tracking-wider text-white/80">
          <span class="inline-block size-1.5 rounded-full" style="background: var(--brand)"></span>
          New vibes
        </span>

                <h1 class="mt-4 text-4xl font-extrabold tracking-tight sm:text-5xl lg:text-6xl">
                    {{ $artistName }}
                </h1>

                <p class="mt-4 max-w-xl text-lg leading-7 text-slate-300">
                    {{ $brandTagline }}
                </p>

                <div class="mt-8 flex flex-wrap items-center gap-4">
                    <a
                        href="https://open.spotify.com/artist/{{ $artistId }}"
                        class="group inline-flex items-center gap-2 rounded-2xl px-5 py-3 text-sm font-semibold text-slate-900 shadow-lg transition
                   bg-white hover:translate-y-[-1px] hover:shadow-xl"
                        style="background: linear-gradient(180deg, #fff, #f6f7fb);">
                        <svg class="size-4 opacity-70 group-hover:opacity-100" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path d="M12 0C5.372 0 0 5.373 0 12c0 6.628 5.372 12 12 12s12-5.372 12-12C24 5.373 18.628 0 12 0zm5.51 17.25a.89.89 0 0 1-1.225.295c-3.357-2.05-7.584-2.516-12.574-1.38a.89.89 0 1 1-.394-1.738c5.383-1.222 10.026-.7 13.664 1.49a.89.89 0 0 1 .529 1.333zm1.75-3.18a1.112 1.112 0 0 1-1.53.37c-3.847-2.356-9.718-3.042-14.293-1.672a1.113 1.113 0 1 1-.635-2.134c5.212-1.548 11.648-.79 15.997 1.877a1.112 1.112 0 0 1 .463 1.56zm.147-3.286c-4.288-2.546-11.399-2.78-15.51-1.54a1.334 1.334 0 1 1-.76-2.56c4.75-1.41 12.605-1.138 17.56 1.806a1.333 1.333 0 1 1-1.29 2.294z"/>
                        </svg>
                        Listen on Spotify
                    </a>

                    <a
                        href="#musica"
                        class="inline-flex items-center gap-2 rounded-2xl border border-white/10 bg-white/5 px-5 py-3 text-sm font-semibold text-white/90 backdrop-blur
                   transition hover:bg-white/10 hover:text-white">
                        Ver álbumes
                    </a>
                </div>

                <dl class="mt-10 grid grid-cols-3 gap-4 max-w-md">
                    <div class="rounded-2xl border border-white/10 bg-white/5 p-4 text-center">
                        <dt class="text-xs text-white/60">Álbumes/Singles</dt>
                        <dd class="mt-1 text-2xl font-bold">10+</dd>
                    </div>
                    <div class="rounded-2xl border border-white/10 bg-white/5 p-4 text-center">
                        <dt class="text-xs text-white/60">Canciones</dt>
                        <dd class="mt-1 text-2xl font-bold">50+</dd>
                    </div>
                    <div class="rounded-2xl border border-white/10 bg-white/5 p-4 text-center">
                        <dt class="text-xs text-white/60">BPM favoritos</dt>
                        <dd class="mt-1 text-2xl font-bold">92–96</dd>
                    </div>
                </dl>
            </div>

            <div class="relative">
                <div class="aspect-square w-full rounded-3xl border border-white/10 bg-white/5 shadow-2xl ring-1 ring-black/5 overflow-hidden">
                    <!-- Placeholder hero art (puedes inyectar $ogImage si lo deseas) -->
                    <div class="rounded-2xl overflow-hidden glow border border-white/10">
                        <!-- Embed del artista: muestra top tracks automáticamente -->
                        <iframe style="border-radius:0"
                                src="https://open.spotify.com/embed/artist/{{$artistId}}?utm_source=generator&theme=0" width="100%" height="480" allowfullscreen="" allow="autoplay; clipboard-write; encrypted-media; fullscreen; picture-in-picture" loading="lazy"></iframe>
                    </div>
                </div>
                <div class="pointer-events-none absolute -bottom-6 -left-6 hidden md:block">
                    <div class="rounded-2xl border border-white/10 bg-white/5 px-4 py-2 text-xs text-white/80 shadow">
                        🎧 Diferentes generos musicales
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
