<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-100 dark:bg-[#212121]">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Super Admin Dashboard</title>

    {{-- Tailwind CSS --}}
    @vite('resources/css/app.css')

    {{-- Bootstrap Icons CSS CDN --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <style>
        /* Custom dark mode sidebar background */
        .dark aside {
            background-color: #171717;
            border-color: #212121;
        }
    </style>
</head>

<body class="h-full flex text-gray-800 dark:text-gray-200">

    <!-- Sidebar -->
    <aside id="sidebar"
        class="fixed inset-y-0 left-0 z-30 w-64 bg-white border-r border-gray-200 p-6 overflow-y-auto
    transform -translate-x-full transition-transform duration-300 ease-in-out
    md:translate-x-0 md:static md:inset-auto md:translate-x-0
    dark:bg-[#171717] dark:border-[#212121]">

        <!-- Logo -->
        <div class="mb-10 flex items-center space-x-4">
            <img src="{{ asset('images/GOFTECH.png') }}" alt="Goftech Logo" class="h-20 w-20 object-contain" />
            {{-- <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-200 select-none">Goftech</h1> --}}
        </div>

        <!-- Nav -->
        <nav class="flex flex-col space-y-4 text-gray-700 dark:text-gray-300 text-sm font-medium">
            <a href="{{ route('superadmin.dashboard') }}"
                class="flex items-center space-x-2 hover:text-indigo-600 dark:hover:text-indigo-400 cursor-pointer">
                <i class="bi bi-speedometer2 text-lg"></i>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('superadmin.school') }}"
                class="flex items-center space-x-2 hover:text-indigo-600 dark:hover:text-indigo-400 cursor-pointer">
                <i class="bi bi-buildings-fill"></i>
                <span>Schools</span>
            </a>
            <a href="#"
                class="flex items-center space-x-2 hover:text-indigo-600 dark:hover:text-indigo-400 cursor-pointer">
                <i class="bi bi-gear text-lg"></i>
                <span>Settings</span>
            </a>
            <a href="{{ route('logout.perform') }}"
                class="flex items-center space-x-2 hover:text-indigo-600 dark:hover:text-indigo-400 cursor-pointer">
                <i class="bi bi-box-arrow-right"></i> <span>Logout</span>
            </a>

        </nav>
    </aside>

    <!-- Main content -->
    <div class="flex-1 flex flex-col min-h-screen">
        <!-- Header -->
        <header
            class="flex items-center justify-between bg-white shadow px-6 py-4 border-b border-gray-200 fixed top-0 left-0 right-0 z-20
        dark:bg-[#212121] dark:border-[#171717]">

            <div class="flex items-center space-x-4">
                <!-- Hamburger -->
                <button id="sidebarToggle" aria-label="Toggle sidebar"
                    class="md:hidden text-gray-600 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 rounded font-bold text-xl leading-none select-none">
                    <i class="bi bi-list"></i>
                </button>
                <h1 class="text-xl font-semibold text-gray-800 dark:text-gray-200">GOFTECH</h1>
            </div>

            <div class="flex items-center space-x-4">
                <!-- Dark mode toggle -->
                <button id="darkModeToggle" aria-label="Toggle dark mode"
                    class="text-gray-600 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 rounded px-3 py-1 select-none font-semibold">
                    <i id="darkIcon" class="bi bi-moon-fill block"></i>
                    <i id="lightIcon" class="bi bi-sun-fill hidden"></i>
                </button>

                <span class="hidden sm:inline select-none">
                    {{ auth()->user()->name ?? 'Goftech' }}
                </span>
                <div class="w-8 h-8 rounded-full overflow-hidden select-none">
                    @if (auth()->user() && auth()->user()->profile_image)
                        <img src="{{ asset('storage/' . auth()->user()->profile_image) }}" alt="Profile"
                            class="w-full h-full object-cover" />
                    @else
                        <div class="w-full h-full bg-indigo-600 flex items-center justify-center font-bold text-white">
                            @php
                                $name = trim(auth()->user()->name ?? 'SA');
                                $parts = preg_split('/\s+/', $name);
                                $initials = '';
                                foreach ($parts as $part) {
                                    $initials .= strtoupper(substr($part, 0, 1));
                                }
                                $initials = substr($initials, 0, 2);
                            @endphp
                            {{ $initials }}
                        </div>
                    @endif
                </div>
            </div>

        </header>

        <main class="flex-1 p-2 pt-20 bg-gray-100 dark:bg-[#212121] overflow-auto">
            @yield('content')
        </main>

    </div>

    <script>
        // Sidebar toggle
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebar = document.getElementById('sidebar');
        sidebarToggle.addEventListener('click', () => {
            sidebar.classList.toggle('-translate-x-full');
        });

        // Dark mode toggle
        const darkToggle = document.getElementById('darkModeToggle');
        const htmlEl = document.documentElement;
        const darkIcon = document.getElementById('darkIcon');
        const lightIcon = document.getElementById('lightIcon');

        // Load saved mode from localStorage or system preference
        const savedMode = localStorage.getItem('theme');
        if (savedMode === 'dark' || (!savedMode && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            htmlEl.classList.add('dark');
            darkIcon.classList.add('hidden');
            lightIcon.classList.remove('hidden');
        } else {
            htmlEl.classList.remove('dark');
            darkIcon.classList.remove('hidden');
            lightIcon.classList.add('hidden');
        }

        darkToggle.addEventListener('click', () => {
            htmlEl.classList.toggle('dark');
            const isDark = htmlEl.classList.contains('dark');
            localStorage.setItem('theme', isDark ? 'dark' : 'light');

            if (isDark) {
                darkIcon.classList.add('hidden');
                lightIcon.classList.remove('hidden');
            } else {
                darkIcon.classList.remove('hidden');
                lightIcon.classList.add('hidden');
            }
        });
    </script>
</body>

</html>
