<!-- CONTACTO -->
<section id="contacto" class="py-20 bg-slate-900 border-t border-white/10">
    <div class="max-w-6xl mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold text-white">Contacto</h2>
            <p class="mt-2 text-slate-400">¿Quieres colaborar, preguntar o simplemente saludar? Escríbenos aquí.</p>
        </div>

        <div class="grid md:grid-cols-2 gap-10">
            <!-- Información de contacto -->
            <div class="space-y-6">
                <div>
                    <h3 class="text-lg font-semibold text-white">Redes Sociales</h3>
                    <div class="flex gap-4 mt-2">
                        @if (isset($spotify))
                        <a href="{{ $spotify }}}" target="_blank" class="text-slate-400 hover:text-cyan-400">Spotify</a>
                        @endif
                        @if (isset($facebook))
                        <a href="{{ $facebook }}" target="_blank" class="text-slate-400 hover:text-cyan-400">Facebook</a>
                        @endif
                        @if (isset($instagram))
                        <a href="{{ $instagram }}" target="_blank" class="text-slate-400 hover:text-cyan-400">Instagram</a>
                        @endif
                        @if (isset($youtube))
                        <a href="{{ $youtube }}" target="_blank" class="text-slate-400 hover:text-cyan-400">YouTube</a>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Formulario -->
            <form action="{{ route('contact.store') }}" method="POST" class="bg-slate-950/60 p-6 rounded-2xl border border-white/10 space-y-4">
                @csrf

                <div>
                    <label for="name" class="block text-sm font-medium text-slate-300">Nombre</label>
                    <input type="text" id="name" name="name" required
                           value="{{ old('name') }}"
                           class="mt-1 w-full rounded-lg bg-slate-900 border border-white/10 px-3 py-2 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-cyan-400">
                    @error('name') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-slate-300">Email</label>
                    <input type="email" id="email" name="email" required
                           value="{{ old('email') }}"
                           class="mt-1 w-full rounded-lg bg-slate-900 border border-white/10 px-3 py-2 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-cyan-400">
                    @error('email') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="message" class="block text-sm font-medium text-slate-300">Mensaje</label>
                    <textarea id="message" name="message" rows="4" required
                              class="mt-1 w-full rounded-lg bg-slate-900 border border-white/10 px-3 py-2 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-cyan-400">{{ old('message') }}</textarea>
                    @error('message') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <button type="submit" class="w-full py-3 rounded-lg bg-cyan-500 text-white font-semibold hover:bg-cyan-400 transition">
                    Enviar mensaje
                </button>

                @if(session('status'))
                    <p class="text-green-400 text-sm mt-3">{{ session('status') }}</p>
                @endif
            </form>

        </div>
    </div>
</section>
