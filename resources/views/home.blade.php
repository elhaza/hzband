<x-layout>
    <main>
        <x-partials.hero
            artist-name="HZ Band"
            artist-id="5VjCPceVjBTwlzewmaNAvd"
            brand-tagline="Canciones que suenan / Emociones que sanan."
        />
        <x-partials.albums
            artist-id="5VjCPceVjBTwlzewmaNAvd"
            :albums="$albums"
        />
    </main>
</x-layout>
