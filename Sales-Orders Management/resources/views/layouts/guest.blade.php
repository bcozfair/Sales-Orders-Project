<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom Styles -->
    <style>
        body {
            font-family: 'Figtree', sans-serif;
            background: linear-gradient(135deg, #ff9a9e, #fad0c4);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-container {
            width: 100%;
            max-width: 420px;
            background: #fff;
            padding: 2rem;
            border-radius: 16px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
            text-align: center;
        }

        .login-container h1 {
            font-size: 26px;
            font-weight: bold;
            color: #ff647f;
            margin-bottom: 1rem;
        }

        .form-control {
            border-radius: 8px;
            border: 2px solid #ff647f;
            transition: 0.3s;
        }

        .form-control:focus {
            border-color: #ff3366;
            box-shadow: 0 0 8px rgba(255, 51, 102, 0.5);
        }

        .btn-custom {
            background: linear-gradient(90deg, #ff647f, #ff3366);
            border: none;
            border-radius: 8px;
            padding: 12px;
            font-weight: 600;
            color: white;
            width: 100%;
            transition: 0.3s;
        }

        .btn-custom:hover {
            background: linear-gradient(90deg, #ff3366, #ff0033);
            transform: scale(1.05);
        }

        .register-link, .forgot-password {
            font-size: 14px;
            color: #ff647f;
            text-decoration: none;
            transition: 0.3s;
        }

        .register-link:hover, .forgot-password:hover {
            color: #ff3366;
            text-decoration: underline;
        }
    </style>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans text-gray-900 antialiased">      
    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="login-container">
            <h1>Sales & Order Management System</h1>
            <div class="mx-auto d-flex justify-content-center">
                <a href="/">
                    <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
                </a>
            </div>

            <div class="mt-4">
                {{ $slot }}
            </div>
        </div>
    </div>
</body>
</html>
