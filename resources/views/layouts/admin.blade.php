{{-- resources/views/layouts/admin.blade.php --}}
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - @yield('title')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .sidebar-item:hover {
            background: linear-gradient(90deg, rgba(59,130,246,0.1) 0%, rgba(59,130,246,0) 100%);
        }
        .sidebar-item.active {
            background: linear-gradient(90deg, rgba(59,130,246,0.2) 0%, rgba(59,130,246,0) 100%);
            border-left: 3px solid #3b82f6;
        }
        .stat-card {
            transition: transform 0.2s;
        }
        .stat-card:hover {
            transform: translateY(-5px);
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div class="w-64 bg-gray-900 text-white flex-shrink-0 overflow-y-auto">
            <div class="p-5">
                <div class="flex items-center space-x-2 mb-8">
                    <i class="fas fa-shield-alt text-2xl text-blue-400"></i>
                    <h2 class="text-xl font-bold">Admin Panel</h2>
                </div>
                
                <nav class="space-y-1">
                    <a href="{{ route('admin.dashboard') }}" class="sidebar-item flex items-center px-4 py-3 rounded-lg transition {{ request()->routeIs('admin.dashboard') ? 'active bg-gray-800' : 'hover:bg-gray-800' }}">
                        <i class="fas fa-chart-line w-5 mr-3"></i>
                        <span>Дашборд</span>
                    </a>
                    
                    <a href="{{ route('admin.users') }}" class="sidebar-item flex items-center px-4 py-3 rounded-lg transition {{ request()->routeIs('admin.users*') ? 'active bg-gray-800' : 'hover:bg-gray-800' }}">
                        <i class="fas fa-users w-5 mr-3"></i>
                        <span>Пользователи</span>
                    </a>
                    
                    <a href="{{ route('admin.tours') }}" class="sidebar-item flex items-center px-4 py-3 rounded-lg transition {{ request()->routeIs('admin.tours*') ? 'active bg-gray-800' : 'hover:bg-gray-800' }}">
                        <i class="fas fa-plane w-5 mr-3"></i>
                        <span>Туры</span>
                    </a>
                    
                    <a href="{{ route('admin.hotels') }}" class="sidebar-item flex items-center px-4 py-3 rounded-lg transition {{ request()->routeIs('admin.hotels*') ? 'active bg-gray-800' : 'hover:bg-gray-800' }}">
                        <i class="fas fa-hotel w-5 mr-3"></i>
                        <span>Отели</span>
                    </a>
                    
                    <a href="{{ route('admin.bookings') }}" class="sidebar-item flex items-center px-4 py-3 rounded-lg transition {{ request()->routeIs('admin.bookings*') ? 'active bg-gray-800' : 'hover:bg-gray-800' }}">
                        <i class="fas fa-suitcase w-5 mr-3"></i>
                        <span>Бронирования</span>
                    </a>
                    
                    <a href="{{ route('admin.reviews') }}" class="sidebar-item flex items-center px-4 py-3 rounded-lg transition {{ request()->routeIs('admin.reviews*') ? 'active bg-gray-800' : 'hover:bg-gray-800' }}">
                        <i class="fas fa-star w-5 mr-3"></i>
                        <span>Отзывы</span>
                    </a>
                    
                    <a href="{{ route('admin.logs') }}" class="sidebar-item flex items-center px-4 py-3 rounded-lg transition {{ request()->routeIs('admin.logs*') ? 'active bg-gray-800' : 'hover:bg-gray-800' }}">
                        <i class="fas fa-history w-5 mr-3"></i>
                        <span>Логи</span>
                    </a>
                </nav>
                
                <hr class="my-6 border-gray-700">
                
                <a href="{{ route('home') }}" class="flex items-center px-4 py-3 rounded-lg hover:bg-gray-800 transition">
                    <i class="fas fa-globe w-5 mr-3"></i>
                    <span>На сайт</span>
                </a>
                
                <form method="POST" action="{{ route('logout') }}" class="mt-2">
                    @csrf
                    <button type="submit" class="w-full flex items-center px-4 py-3 rounded-lg hover:bg-gray-800 transition">
                        <i class="fas fa-sign-out-alt w-5 mr-3"></i>
                        <span>Выйти</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 overflow-y-auto">
            <!-- Header -->
            <div class="bg-white shadow-sm px-6 py-4 sticky top-0 z-10">
                <div class="flex justify-between items-center">
                    <h1 class="text-2xl font-semibold text-gray-800">@yield('title')</h1>
                    <div class="flex items-center space-x-4">
                        <div class="text-right">
                            <p class="text-sm font-medium text-gray-700">{{ Auth::user()->full_name }}</p>
                            <p class="text-xs text-gray-500">{{ Auth::user()->role_name }}</p>
                        </div>
                        <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold">
                            {{ substr(Auth::user()->first_name, 0, 1) }}{{ substr(Auth::user()->last_name, 0, 1) }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Flash Messages -->
            @if(session('success'))
                <div class="mx-6 mt-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle mr-3"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="mx-6 mt-4 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded shadow">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle mr-3"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                </div>
            @endif

            <!-- Content -->
            <div class="p-6">
                @yield('content')
            </div>
        </div>
    </div>
</body>
</html>