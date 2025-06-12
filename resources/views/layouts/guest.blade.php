<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <style>
            body {
                background-color: #121212;
                color: #ffffff;
                font-family: 'Figtree', sans-serif;
            }
            .auth-card {
                background-color: #1e1e1e;
                border: 1px solid #444;
                border-radius: 8px;
            }
            .form-control {
                background-color: #2d2d2d;
                border: 1px solid #444;
                color: #ffffff;
            }
            .form-control:focus {
                background-color: #2d2d2d;
                border-color: #007bff;
                color: #ffffff;
                box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
            }
            .btn-primary {
                background-color: #007bff;
                border-color: #007bff;
            }
            .btn-primary:hover {
                background-color: #0056b3;
                border-color: #0056b3;
            }
        </style>
    </head>
    <body>
        <div class="min-vh-100 d-flex flex-column justify-content-center align-items-center py-5">
            <div class="mb-4">
                <a href="/" class="text-decoration-none">
                    <h2 class="text-white">DeepSea Scans</h2>
                </a>
            </div>

            <div class="auth-card p-4" style="width: 100%; max-width: 400px;">
                {{ $slot }}
            </div>
        </div>

        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
