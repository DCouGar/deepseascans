<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'deepseaScans')</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Your Custom CSS -->
    {{-- CSS is now inline below --}}

    <!-- Google Fonts (if needed) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;500;700&display=swap" rel="stylesheet">

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    
    <!-- Inline Styles (restored) -->
    <style>
        /* DeepSeaScans Custom Styles */
        html, body {
            height: 100%;
        }

        body {
            background-color: #121212;
            color: #ffffff;
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen, Ubuntu, Cantarell, "Open Sans", "Helvetica Neue", sans-serif;
            display: flex;
            flex-direction: column;
        }

        main.container {
            flex-grow: 1;
        }

        .container {
            max-width: 1200px;
        }

        /* --- NAVBAR STYLES --- */
        .navbar {
            background-color: #2a4f8b; /* Blue background */
            border-bottom: 1px solid #1a355d; /* Darker blue bottom border */
            padding: 10px 0;
            font-family: 'Nunito', sans-serif; /* Specific font for navbar */
        }

        /* Navbar Brand (Logo & Text) */
        .navbar .navbar-brand {
            color: #cccccc !important;
            font-weight: 700;
            text-decoration: none;
        }
        .navbar .navbar-brand:hover,
        .navbar .navbar-brand span:hover {
             color: #ffffff !important;
        }
        .navbar .navbar-brand img {
             height: 30px; /* Logo height */
             filter: none;
             vertical-align: middle;
        }

        /* Navbar Links */
        .navbar .navbar-nav .nav-link {
            color: #cccccc;
            font-weight: 500;
            transition: color 0.2s ease-in-out;
            padding: 0.5rem 1rem;
        }

        /* Navbar Links Hover/Focus/Active */
        .navbar .navbar-nav .nav-link:hover,
        .navbar .navbar-nav .nav-link:focus,
        .navbar .navbar-nav .nav-link.active {
            color: #ffffff;
        }

        /* Logout Button Styling */
         .navbar .navbar-nav .nav-link-button {
             background: none;
             border: none;
             padding: 0.5rem 1rem;
             color: #cccccc;
             font-weight: 500;
             font-family: 'Nunito', sans-serif;
             cursor: pointer;
             transition: color 0.2s ease-in-out;
         }
         .navbar .navbar-nav .nav-link-button:hover {
              color: #ffffff;
         }

        /* Navbar Toggler Icon */
        .navbar-toggler-icon {
             background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba(204, 204, 204, 1)' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }
        /* Navbar Toggler Button Border */
        .navbar-toggler {
            border-color: rgba(204, 204, 204, 0.4);
        }
        .navbar-toggler:focus {
             box-shadow: 0 0 0 0.25rem rgba(204, 204, 204, 0.25);
        }

        /* --- MANGA GRID STYLES --- */
        .manga-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .manga-item {
            border: 1px solid #444;
            background-color: #1e1e1e;
            border-radius: 5px;
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            display: flex;
            flex-direction: column;
        }

        .manga-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.4);
        }

        .manga-image {
            width: 100%;
            aspect-ratio: 2 / 3;
            object-fit: cover;
            display: block;
            background-color: #282828;
        }

        .manga-info {
            padding: 10px 15px;
            flex-grow: 1;
        }

        .manga-info h5 {
             font-size: 1rem;
             color: #eee;
             font-weight: 600;
             margin-bottom: 0;
             white-space: nowrap;
             overflow: hidden;
             text-overflow: ellipsis;
        }

        .manga-buttons {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 15px 15px 15px;
            margin-top: auto;
            gap: 10px;
        }

        .button { /* General button style within manga cards */
            background-color: #383838;
            color: #e0e0e0;
            border: none;
            padding: 6px 10px;
            border-radius: 4px;
            text-decoration: none;
            font-size: 0.8rem;
            transition: background-color 0.2s ease, color 0.2s ease;
            font-weight: 500;
            text-align: center;
            flex-shrink: 0;
            white-space: nowrap;
        }

        .button:hover {
            background-color: #555;
            color: #fff;
            text-decoration: none;
        }

        /* Footer */
        footer {
            background-color: #1a1a1a;
            padding: 20px 0;
            margin-top: auto;
        }

        footer p {
            color: #cccccc;
            margin: 0;
        }
    </style>

    @yield('styles') {{-- Still useful if a specific page needs extra unique styles --}}
</head>
<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('landing') }}">
                {{-- Use favicon as logo since deepsea.png doesn't exist --}}
                <img src="{{ asset('favicon.ico') }}" alt="Logo">
                <span>DeepSea Scans</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('landing') ? 'active' : '' }}" aria-current="{{ request()->routeIs('landing') ? 'page' : 'false' }}" href="{{ route('landing') }}">Inicio</a>
                    </li>
                    @auth
                        @if(auth()->user()->is_admin)
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">Admin</a>
                            </li>
                        @endif
                        <li class="nav-item">
                            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="nav-link-button">Cerrar sesión</button>
                            </form>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <main class="container py-4">
        @yield('content')
    </main>

    <footer>
        <div class="container text-center">
            <p>© {{ date('Y') }} deepseaScans - Proyecto DAW</p>
        </div>
    </footer>

    <!-- JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>