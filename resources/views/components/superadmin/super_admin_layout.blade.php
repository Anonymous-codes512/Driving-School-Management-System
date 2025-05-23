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
    <!-- Toastr CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">

    <style>
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }

        .scrollbar-hide {
            scrollbar-width: none;
            -ms-overflow-style: none;
        }

        .loader {
            font-size: 1.25rem;
        }

        .dark aside {
            background-color: #171717;
            border-color: #212121;
        }

        #toast-container {
            margin-top: 10px !important;
            z-index: 9999 !important;
        }

        #toast-container>.toast {
            width: auto !important;
            max-width: 550px !important;
            min-width: 200px !important;
            white-space: normal !important;
            word-wrap: break-word !important;
        }

        input[type=number].no-spin::-webkit-inner-spin-button,
        input[type=number].no-spin::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        input[type=number].no-spin {
            -moz-appearance: textfield;
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
            <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-200 select-none">Goftech</h1>
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
            <a href="{{ route('superadmin.subscription') }}"
                class="flex items-center space-x-2 hover:text-indigo-600 dark:hover:text-indigo-400 cursor-pointer">
                <i class="bi bi-bookmark-star"></i>
                <span>Subscriptions</span>
            </a>
            <a href="{{ route('superadmin.subscription_request') }}"
                class="flex items-center space-x-2 hover:text-indigo-600 dark:hover:text-indigo-400 cursor-pointer">
                <i class="bi bi-bookmark-star"></i>
                <span>Subscription Requests</span>
            </a>
            <form id="logout-form" action="{{ route('logout.perform') }}" method="POST" class="inline">
                @csrf
                <button type="submit"
                    class="flex items-center space-x-2 hover:text-indigo-600 dark:hover:text-indigo-400 cursor-pointer">
                    <i class="bi bi-box-arrow-right"></i>
                    <span>Logout</span>
                </button>
            </form>


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

        <main class="flex-1 p-2 pt-20 bg-gray-100 dark:bg-[#212121] overflow-auto scrollbar-hide">
            @yield('content')
        </main>

    </div>

    <!-- jQuery (Toastr depends on jQuery) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "positionClass": "toast-top-center",
            "timeOut": "5000",
            "escapeHtml": false
        };

        @if (session('success'))
            toastr.success({!! json_encode(session('success')) !!});
        @endif

        @if (session('error'))
            toastr.error({!! json_encode(session('error')) !!});
        @endif

        @if (session('info'))
            toastr.info({!! json_encode(session('info')) !!});
        @endif

        @if (session('warning'))
            toastr.warning({!! json_encode(session('warning')) !!});
        @endif

        @if ($errors->any())
            @foreach ($errors->all() as $error)
                toastr.error({!! json_encode($error) !!});
            @endforeach
        @endif
    </script>


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
