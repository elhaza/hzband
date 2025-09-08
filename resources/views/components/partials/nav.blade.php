<!-- NAV -->
<header class="sticky top-0 z-50 backdrop-blur bg-black/40 border-b border-white/10">
    <div class="max-w-6xl mx-auto px-4 py-3 flex items-center justify-between">
        <a href="/" class="flex items-center gap-3 group">
            <!-- Logo -->
            <img src="{{ asset('images/hzband.png') }}"
                 alt="Hz Band Logo"
                 class="object-contain"
                 style="max-width: 100px; max-height: 100px;"
            />

            <div>
                <p class="text-xs text-slate-400 -mt-0.5">{{$brandTagline ?? ''}}</p>
            </div>
        </a>
        <nav class="hidden md:flex items-center gap-6 text-sm">
            <a href="{{route('home')}}#musica" class="hover:text-cyan-300">Música</a>
            <a href="{{route('contact')}}" class="px-3 py-1 rounded-lg bg-cyan-500/20 border border-cyan-400/30 hover:bg-cyan-400/25">Contacto</a>
        </nav>
    </div>
</header>
