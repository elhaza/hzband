<!-- SECCIÓN MÚSICA / Albums -->
  <section id="musica" class="py-16 border-t border-white/10 bg-slate-900/50">
    <div class="max-w-6xl mx-auto px-4">
      <div class="flex items-end justify-between gap-6 flex-wrap">
        <div>
          <h2 class="text-3xl md:text-4xl font-bold">Albums</h2>
          <p class="text-slate-300 mt-2 max-w-2xl">
              Sin importar la temática, buscaremos dejar una huella positiva en quien nos escucha.
          </p>
        </div>

        <a href="https://open.spotify.com/intl-es/artist/{{$artistId}}" target="_blank" class="text-cyan-300 hover:underline">Ver todo en Spotify →</a>
      </div>

      <div class="mt-8 grid md:grid-cols-3 gap-6">
          @foreach ($albums as $album)
                <x-partials.album-single :album-id="$album['spotify_id']" />
          @endforeach
      </div>
    </div>
  </section>
