<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'TravelDream - Ваши мечты о путешествиях')</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .hero-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .tour-card:hover {
            transform: translateY(-5px);
            transition: all 0.3s ease;
        }
        .dropdown-menu {
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.3s ease;
        }
        .dropdown:hover .dropdown-menu {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }
        .admin-badge {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        /* Стили для тост-уведомлений */
        .toast {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            min-width: 300px;
            max-width: 400px;
            animation: slideInRight 0.3s ease-out;
        }
        
        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
        @keyframes slideOutRight {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(100%);
                opacity: 0;
            }
        }
        
        .toast-hide {
            animation: slideOutRight 0.3s ease-out forwards;
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center">
                        <i class="fas fa-plane-departure text-2xl text-blue-600 mr-2"></i>
                        <span class="text-xl font-bold text-gray-800">TravelDream</span>
                    </a>
                </div>
                
                <div class="hidden md:flex space-x-8">
                    <a href="{{ route('home') }}" class="text-gray-600 hover:text-blue-600 font-medium {{ request()->routeIs('home') ? 'text-blue-600' : '' }}">Главная</a>
                    <a href="{{ route('tours.index') }}" class="text-gray-600 hover:text-blue-600 font-medium {{ request()->routeIs('tours.*') ? 'text-blue-600' : '' }}">Туры</a>
                    <a href="{{ route('hotels.index') }}" class="text-gray-600 hover:text-blue-600 font-medium {{ request()->routeIs('hotels.*') ? 'text-blue-600' : '' }}">Отели</a>
                    <a href="{{ route('reviews.index') }}" class="text-gray-600 hover:text-blue-600 font-medium {{ request()->routeIs('reviews.index') ? 'text-blue-600' : '' }}">Отзывы</a>
                </div>

                <div class="flex items-center space-x-4">
                    @auth
                        <!-- Кнопка админ-панели (показывается только для админов и модераторов) -->
                        @if(Auth::user()->role === 'admin' || Auth::user()->role === 'moderator')
                            <a href="{{ route('admin.dashboard') }}" 
                               class="admin-badge text-white px-4 py-2 rounded-lg hover:opacity-90 transition flex items-center space-x-2">
                                <span class="font-semibold text-sm">Админ-панель</span>
                            </a>
                        @endif
                    
                        <!-- Меню пользователя -->
                        <div class="dropdown relative">
                            <button class="flex items-center space-x-2 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                                <i class="fas fa-user"></i>
                                <span>{{ Auth::user()->first_name }}</span>
                                <i class="fas fa-chevron-down text-xs"></i>
                            </button>
                            
                            <!-- Dropdown меню -->
                            <div class="dropdown-menu absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2 z-50 border border-gray-200">
                                <a href="{{ route('profile.show') }}" class="block px-4 py-2 text-gray-800 hover:bg-blue-50 transition">
                                    <i class="fas fa-user-circle mr-2"></i>Профиль
                                </a>
                                <a href="{{ route('bookings.index') }}" class="block px-4 py-2 text-gray-800 hover:bg-blue-50 transition">
                                    <i class="fas fa-suitcase mr-2"></i>Мои бронирования
                                </a>
                                
                                <!-- Дублирующая кнопка админки в выпадающем меню -->
                                @if(Auth::user()->role === 'admin' || Auth::user()->role === 'moderator')
                                    <hr class="my-1">
                                    <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-purple-600 hover:bg-purple-50 transition font-semibold">
                                        <i class="fas fa-shield-alt mr-2"></i>Админ-панель
                                    </a>
                                @endif
                                
                                <hr class="my-1">
                                <form method="POST" action="{{ route('logout') }}" class="block">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-gray-800 hover:bg-blue-50 transition">
                                        <i class="fas fa-sign-out-alt mr-2"></i>Выйти
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <!-- Гостевые кнопки -->
                        <a href="{{ route('login') }}" class="text-gray-600 hover:text-blue-600 font-medium {{ request()->routeIs('login') ? 'text-blue-600' : '' }}">
                            <i class="fas fa-sign-in-alt mr-1"></i>Войти
                        </a>
                        <a href="{{ route('register') }}" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                            <i class="fas fa-user-plus mr-2"></i>Регистрация
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Контейнер для тост-уведомлений (всплывающие слева) -->
    <div id="toastContainer" class="fixed top-5 right-5 z-[9999] flex flex-col gap-3"></div>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-12">
        <div class="max-w-7xl mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-xl font-bold mb-4">TravelDream</h3>
                    <p class="text-gray-400">Ваши мечты о путешествиях становятся реальностью с нами.</p>
                </div>
                
                <div>
                    <h4 class="font-semibold mb-4">Направления</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#" class="hover:text-white">Москва</a></li>
                        <li><a href="#" class="hover:text-white">Екатеринбург</a></li>
                        <li><a href="#" class="hover:text-white">СПБ</a></li>
                        <li><a href="#" class="hover:text-white">Ялта</a></li>
                    </ul>
                </div>
                
                <div>
                    <h4 class="font-semibold mb-4">Контакты</h4>
                    <div class="space-y-2 text-gray-400">
                        <p><i class="fas fa-phone mr-2"></i>+7 (999) 123-45-67</p>
                        <p><i class="fas fa-envelope mr-2"></i>info@traveldream.ru</p>
                        <p><i class="fas fa-map-marker-alt mr-2"></i>Челябинск, ул. МоллиБи, 1</p>
                    </div>
                </div>
                
                <div>
                    <h4 class="font-semibold mb-4">Подписка</h4>
                    <div class="flex">
                        <input type="email" placeholder="Ваш email" class="px-4 py-2 rounded-l-lg w-full text-gray-800">
                        <button class="bg-blue-600 px-4 py-2 rounded-r-lg hover:bg-blue-700">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </div>
                </div>
            </div>
            
            <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; 2025 Mollyb <3</p>
            </div>
        </div>
    </footer>

    <script>
        // Функция показа уведомления
        function showToast(message, type = 'success') {
            const container = document.getElementById('toastContainer');
            if (!container) return;
            
            const toast = document.createElement('div');
            toast.className = 'toast rounded-lg shadow-xl overflow-hidden';
            
            let bgColor = '';
            let icon = '';
            
            if (type === 'success') {
                bgColor = 'bg-gradient-to-r from-green-500 to-green-600';
                icon = '<i class="fas fa-check-circle text-white mr-2"></i>';
            } else if (type === 'error') {
                bgColor = 'bg-gradient-to-r from-red-500 to-red-600';
                icon = '<i class="fas fa-exclamation-circle text-white mr-2"></i>';
            } else if (type === 'warning') {
                bgColor = 'bg-gradient-to-r from-yellow-500 to-yellow-600';
                icon = '<i class="fas fa-exclamation-triangle text-white mr-2"></i>';
            } else {
                bgColor = 'bg-gradient-to-r from-blue-500 to-blue-600';
                icon = '<i class="fas fa-info-circle text-white mr-2"></i>';
            }
            
            toast.innerHTML = `
                <div class="${bgColor} text-white px-4 py-3 flex items-center justify-between">
                    <div class="flex items-center">
                        ${icon}
                        <span class="text-sm font-medium">${message}</span>
                    </div>
                    <button onclick="closeToast(this)" class="ml-4 text-white hover:text-gray-200 transition">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="bg-white h-1 animate-progress" style="width: 100%; animation: progress 3s linear forwards;"></div>
            `;
            
            container.appendChild(toast);
            
            // Автоматическое закрытие через 3 секунды
            setTimeout(() => {
                if (toast && toast.parentNode) {
                    toast.classList.add('toast-hide');
                    setTimeout(() => {
                        if (toast && toast.parentNode) toast.remove();
                    }, 300);
                }
            }, 3000);
        }
        
        // Функция закрытия тоста
        function closeToast(btn) {
            const toast = btn.closest('.toast');
            if (toast) {
                toast.classList.add('toast-hide');
                setTimeout(() => {
                    if (toast && toast.parentNode) toast.remove();
                }, 300);
            }
        }
        
        // Показываем уведомления из сессии
        @if(session('success'))
            showToast('{{ session('success') }}', 'success');
        @endif
        
        @if(session('error'))
            showToast('{{ session('error') }}', 'error');
        @endif
        
        @if(session('warning'))
            showToast('{{ session('warning') }}', 'warning');
        @endif
    </script>
    
    <style>
        @keyframes progress {
            from {
                width: 100%;
            }
            to {
                width: 0%;
            }
        }
    </style>

    <script>
        // Простой JavaScript для плавной прокрутки
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth'
                    });
                }
            });
        });
    </script>
</body>
</html>