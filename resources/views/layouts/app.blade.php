<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard')</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .dropdown-menu {
            display: none;
        }
        .dropdown-menu.show {
            display: block;
        }
        .mobile-menu {
            display: none;
        }
        .mobile-menu.show {
            display: block;
        }
    </style>
</head>
<body class="bg-gray-100">
    <nav class="bg-blue-600 text-white p-4">
        <div class="container mx-auto flex justify-between items-center">
            <a href="#" class="text-2xl font-bold">{{ auth()->user()->role === 'admin' ? 'AdminPanel' : 'UserPanel' }}</a>
            <div class="hidden md:flex space-x-4 items-center">
                @if(auth()->user()->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-blue-200">Dashboard</a>
                    <a href="{{ route('admin.tasks.index') }}" class="hover:text-blue-200">Tasks</a>
                    <a href="{{ route('users.index') }}" class="hover:text-blue-200">Users</a>
                @else
                    <a href="{{ route('dashboard') }}" class="hover:text-blue-200">Dashboard</a>
                    <a href="{{ route('tasks.index') }}" class="hover:text-blue-200">My Tasks</a>
                @endif
                <div class="relative">
                    <button id="profileDropdown" class="flex items-center hover:text-blue-200 focus:outline-none">
                        <span class="mr-1">{{ auth()->user()->name }}</span>
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div id="profileMenu" class="dropdown-menu absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-10">
                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Edit Profile</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Logout</button>
                        </form>
                    </div>
                </div>
            </div>
            <button id="mobileMenuBtn" class="md:hidden">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
                </svg>
            </button>
        </div>
    </nav>

    <div id="mobileMenu" class="mobile-menu bg-blue-500 text-white p-4 md:hidden">
        @if(auth()->user()->role === 'admin')
            <a href="{{ route('admin.dashboard') }}" class="block py-2">Dashboard</a>
            <a href="{{ route('admin.tasks.index') }}" class="block py-2">Tasks</a>
            <a href="{{ route('users.index') }}" class="block py-2">Users</a>
        @else
            <a href="{{ route('dashboard') }}" class="block py-2">Dashboard</a>
            <a href="{{ route('tasks.index') }}" class="block py-2">My Tasks</a>
        @endif
        <a href="{{ route('profile.edit') }}" class="block py-2">Edit Profile</a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="block w-full text-left py-2">Logout</button>
        </form>
    </div>

    <main class="container mx-auto p-4">
        @yield('content')
    </main>

    <script>
        const profileDropdown = document.getElementById('profileDropdown');
        const profileMenu = document.getElementById('profileMenu');
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const mobileMenu = document.getElementById('mobileMenu');

        profileDropdown.addEventListener('click', (e) => {
            e.stopPropagation();
            profileMenu.classList.toggle('show');
        });

        mobileMenuBtn.addEventListener('click', () => {
            mobileMenu.classList.toggle('show');
        });

        // Close dropdown and mobile menu when clicking outside
        document.addEventListener('click', (event) => {
            if (!profileDropdown.contains(event.target) && !profileMenu.contains(event.target)) {
                profileMenu.classList.remove('show');
            }
            if (!mobileMenuBtn.contains(event.target) && !mobileMenu.contains(event.target)) {
                mobileMenu.classList.remove('show');
            }
        });
    </script>
</body>
</html>