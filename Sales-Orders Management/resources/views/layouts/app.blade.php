<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
    <link rel="icon" type="image/png" href="../assets/img/favicon.png">

    <!--     Fonts and icons     -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    <!-- Nucleo Icons -->
    <link href="https://demos.creative-tim.com/argon-dashboard-pro/assets/css/nucleo-icons.css" rel="stylesheet" />
    <link href="https://demos.creative-tim.com/argon-dashboard-pro/assets/css/nucleo-svg.css" rel="stylesheet" />
    <!-- Font Awesome Icons -->
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
        
    <title>Sales & Order Management System</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Include Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])



    <style>
        /* Sidebar Styling */
        .sidebar {
            width: 250px;
            background: linear-gradient(135deg, #1c1c2e, #2a2a3a);
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.3);
            transition: width 0.3s ease-in-out;
        }

        /* Avatar Styling */
        .avatar-container {
            position: relative;
            width: 90px;
            height: 90px;
            margin: auto;
        }

        .avatar {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
            border: 3px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 0 12px rgba(255, 255, 255, 0.3);
            transition: transform 0.3s;
        }

        .avatar:hover {
            transform: scale(1.1);
        }

        /* Status Indicator */
        .status-indicator {
            position: absolute;
            bottom: 5px;
            right: 5px;
            width: 15px;
            height: 15px;
            background: #28a745;
            border: 2px solid white;
            border-radius: 50%;
            box-shadow: 0 0 8px rgba(40, 167, 69, 0.8);
        }

        /* Menu Styling */
        .menu-item {
            color: #ffffff;
            display: flex;
            align-items: center;
            padding: 10px 15px;
            font-size: 16px;
            border-radius: 8px;
            transition: background 0.3s, transform 0.2s;
        }

        .menu-item:hover {
            background: rgba(255, 255, 255, 0.1);
            transform: translateX(5px);
            color: #ffcc00;
        }

        /* Active Menu */
        .menu-item.active {
            background: rgba(255, 255, 255, 0.2);
            box-shadow: inset 0 0 10px rgba(255, 255, 255, 0.3);
            border-left: 4px solid #ffcc00;
        }

        /* Logout Button */
        .logout-btn {
            width: 100%;
            padding: 12px;
            font-size: 16px;
            transition: background 0.3s, transform 0.2s;
        }

        .logout-btn:hover {
            background: #dc3545;
            transform: scale(1.05);
        }
        
    </style>

    
</head>

<body class="font-sans antialiased">
    <div class="d-flex">

        <!-- Sidebar -->
        <nav id="sidebar" class="d-flex flex-column p-3 text-white vh-100 sidebar" style="position: sticky; top: 0; z-index: 1000;">
            <!-- Admin Profile Section -->
            <div class="text-center mb-4">
                <p class="text-light small">{{ Auth::user()->email }}</p>
                <div class="avatar-container mt-2">
                    <img src="https://i0.wp.com/www.korseries.com/wp-content/uploads/2023/04/iu-dream-interview-1.jpg?resize=750%2C1125&ssl=1"
                        alt="Admin Avatar" class="avatar">
                    <span class="status-indicator"></span>
                </div>
                <p class="text-light small mt-2">Admin</p>
            </div>

            <!-- Menu List -->
            <ul class="nav flex-column flex-grow-1">
                <li class="nav-item">
                    <a class="nav-link menu-item {{ Route::currentRouteName() == 'dashboard' ? 'active' : '' }}"
                        href="{{ route('dashboard') }}">
                        <i class="fa-solid fa-table-list me-3"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-item {{ Route::currentRouteName() == 'orders.index' ? 'active' : '' }}"
                        href="{{ route('orders.index') }}">
                        <i class="fa-solid fa-file-invoice me-3"></i> Orders
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-item {{ Route::currentRouteName() == 'products.index' ? 'active' : '' }}"
                        href="{{ route('products.index') }}">
                        <i class="fa-solid fa-basket-shopping me-3"></i> Products
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-item {{ Route::currentRouteName() == 'report' ? 'active' : '' }}"
                        href="{{ route('report') }}">
                        <i class="fas fa-chart-line me-3"></i> Report
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-item {{ Route::currentRouteName() == 'profile.edit' ? 'active' : '' }}"
                        href="{{ route('profile.edit') }}">
                        <i class="fas fa-cogs me-3"></i> Setting
                    </a>
                </li>
            </ul>

            <!-- Logout Button -->
            <div class="mt-auto">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-danger logout-btn">
                        <i class="fas fa-sign-out-alt me-2"></i> {{ __('Log Out') }}
                    </button>
                </form>
            </div>
        </nav>

        <!-- Main Content -->
        <div class="flex-grow-1">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main class="p-3">
                {{ $slot }}
            </main>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
