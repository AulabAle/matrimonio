@props(['title' => null, 'hideScrollOnDesktop' => false])
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="overflow-x-hidden {{ $hideScrollOnDesktop ? 'lg:overflow-y-hidden lg:h-screen' : '' }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ $title ?? 'Matrimonio Monica & Erasmo' }}</title>

        @fonts

        <!-- Styles / Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-zinc-50 text-charcoal font-sans antialiased min-h-screen {{ $hideScrollOnDesktop ? 'lg:h-screen lg:overflow-y-hidden' : '' }} flex flex-col justify-between relative selection:bg-gold-light selection:text-charcoal-dark">
        <!-- Background decorative glows -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none z-0">
            <div class="absolute top-[-20%] left-[-10%] w-[50vw] h-[50vw] rounded-full bg-sage-light/10 blur-[120px]"></div>
            <div class="absolute bottom-[-10%] right-[-10%] w-[50vw] h-[50vw] rounded-full bg-gold-light/15 blur-[120px]"></div>
        </div>

        <!-- Navbar component -->
        <x-navbar />

        <!-- Main Content slot -->
        <main class="grow flex flex-col justify-center max-w-6xl w-full mx-auto px-4 sm:px-6 py-6 sm:py-12 {{ $hideScrollOnDesktop ? 'lg:py-4' : '' }} z-10">
            {{ $slot }}
        </main>

        <footer class="w-full py-8 {{ $hideScrollOnDesktop ? 'lg:py-4 lg:pb-4' : '' }} pb-[calc(2rem+env(safe-area-inset-bottom))] border-t border-zinc-200/60 bg-white z-10 mt-auto">
            <div class="max-w-6xl mx-auto px-6 flex justify-center items-center text-xs text-charcoal-light">
                <a href="{{ route('area-riservata') }}" class="hover:text-charcoal transition-colors">&copy; 2026 Invito Matrimonio Monica ed Erasmo</a>
            </div>
        </footer>
    </body>
</html>
