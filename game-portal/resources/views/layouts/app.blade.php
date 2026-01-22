{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Games Portal') }}</title>

    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=orbitron:400,500,700,900|rajdhani:400,500,600,700&display=swap" rel="stylesheet" />

    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    
    
    <link href="{{ asset('css/admin-dash.css') }}" rel="stylesheet">

    
    @stack('styles')

   
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>

    <div class="game-background">
        <div class="grid-overlay"></div>
        <div class="particles"></div>
        <div class="glow-orb glow-orb-1"></div>
        <div class="glow-orb glow-orb-2"></div>
        <div class="glow-orb glow-orb-3"></div>
    </div>

   
    @include('layouts.navigation')

    
    <main class="main-content">
        @yield('content')
    </main>

    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    
    <script>
        
        const particlesContainer = document.querySelector('.particles');
        if (particlesContainer) {
            for (let i = 0; i < 50; i++) {
                const particle = document.createElement('div');
                particle.className = 'particle';
                particle.style.left = Math.random() * 100 + '%';
                particle.style.top = Math.random() * 100 + '%';
                particle.style.animationDelay = Math.random() * 5 + 's';
                particle.style.animationDuration = (Math.random() * 3 + 2) + 's';
                particlesContainer.appendChild(particle);
            }
        }
    </script>

    
    @stack('scripts')
</body>
</html>