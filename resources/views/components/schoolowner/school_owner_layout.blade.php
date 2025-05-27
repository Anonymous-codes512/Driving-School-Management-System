<!DOCTYPE html>
<html lang="en" class="h-full bg-[#f9faff]">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>School Owner Dashboard</title>

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

        aside {
            background-color: #f9faff;
            border-color: #e5e7eb;
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

        header {
            background-color: #ffffff;
            border-bottom: 1px solid #e5e7eb;
            box-shadow: 0 1px 2px rgb(0 0 0 / 0.05);
        }

        /* Select menu styling */
        select {
            background-color: #f9faff;
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
            padding: 0.5rem 0.75rem;
            font-weight: 600;
            font-size: 0.875rem;
            color: #374151;
            transition: border-color 0.3s ease;
            appearance: none;
            background-image: url("data:image/svg+xml,%3csvg fill='none' stroke='%23374151' stroke-width='2' viewBox='0 0 24 24' xmlns='http://www.w3.org/2000/svg'%3e%3cpath stroke-linecap='round' stroke-linejoin='round' d='M19 9l-7 7-7-7'%3e%3c/path%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 0.75rem center;
            background-size: 1.5em 1.5em;
            cursor: pointer;
        }

        select:focus {
            outline: none;
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.3);
        }

        /* Notification red dot */
        #notificationDot {
            width: 0.5rem;
            height: 0.5rem;
            animation: pulse 2s infinite;
            position: absolute;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.5;
            }
        }

        /* Sidebar nav scroll area fix */
        aside nav {
            max-height: calc(100vh - 7rem);
            overflow-y: auto;
        }

        /* Notification dropdown fixed below header */
        #notificationDropdown {
            position: fixed;
            top: 6rem;
            /* adjust header height */
            right: 15rem;
            /* adjust horizontal position */
            width: 18rem;
            /* 288px */
            max-height: 20rem;
            /* 320px */
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 0.5rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1),
                0 4px 6px -4px rgba(0, 0, 0, 0.1);
            overflow-y: auto;
            z-index: 9999;
            display: none;
            scrollbar-width: none;
            -ms-overflow-style: none;
        }

        /* Prevent content under scrollbar */
        #notificationDropdown ul {
            padding-left: 0;
            margin-bottom: 0;
            padding-right: 1rem;
            /* space for scrollbar */
        }

        /* Optional: Customize WebKit scrollbar for better UX */
        #notificationDropdown::-webkit-scrollbar {
            display: none;
        }
    </style>
</head>

<body class="h-full flex text-gray-800 bg-[#f9faff] overflow-x-hidden">
    <!-- Global Loader Overlay -->
    <div id="globalLoader" class="fixed inset-0 flex items-center justify-center z-50 hidden backdrop-blur-sm">
        <div class="loader ease-linear rounded-full border-8 border-t-8 border-gray-400 border-t-black h-20 w-20"></div>
    </div>

    <style>
        /* Spinner with black top border for the rotating part */
        .loader {
            border-top-color: black;
            /* spinner color black */
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>

    <!-- Sidebar -->
    <aside id="sidebar"
        class="fixed inset-y-0 left-0 z-30 w-64 border-r border-gray-200 p-6 flex flex-col bg-[#f9faff]">

        <!-- Logo fixed at top -->
        @php
            $schoolLogo = asset('images/GOFTECH.png'); // default
            $schoolName = 'Goftech';

            if (auth()->user()) {
                $user = auth()->user();
                if ($user->school && $user->school->logo_path) {
                    $schoolLogo = asset('storage/' . $user->school->logo_path);
                    $schoolName = $user->school->name;
                }
            }
        @endphp

        <div class="flex-shrink-0 mb-5 flex items-center space-x-4">
            <img src="{{ $schoolLogo }}" alt="School Logo" class="h-20 w-20 object-contain mx-auto" />
            {{-- <h1 class="text-2xl font-bold text-gray-900 select-none">{{ $schoolName }}</h1> --}}
        </div>

        @php
            $currentRoute = Route::currentRouteName();
            $navItems = [
                ['name' => 'Dashboard', 'route' => 'schoolowner.dashboard', 'icon' => 'bi-grid-3x3-gap-fill'],
                ['name' => 'Admissions', 'route' => 'schoolowner.admissions', 'icon' => 'bi-file-earmark-person'],
                ['name' => 'Cars', 'route' => 'schoolowner.cars', 'icon' => 'bi-car-front-fill'],
                ['name' => 'Courses', 'route' => 'schoolowner.courses', 'icon' => 'bi-journal-text'],
                ['name' => 'Instructors', 'route' => 'schoolowner.instructors', 'icon' => 'bi-people-fill'],
                ['name' => 'Students', 'route' => 'schoolowner.students', 'icon' => 'bi-people'],
                ['name' => 'Invoices', 'route' => 'schoolowner.invoices', 'icon' => 'bi-currency-dollar'],
                ['name' => 'Banners', 'route' => 'schoolowner.banners', 'icon' => 'bi-image'],
                ['name' => 'Expenses', 'route' => 'schoolowner.expenses', 'icon' => 'bi-cash-stack'],
                ['name' => 'Attendance', 'route' => 'schoolowner.attendance', 'icon' => 'bi-check2-square'],
                ['name' => 'Classes', 'route' => 'schoolowner.classes', 'icon' => 'bi-calendar3'],
                ['name' => 'Leaves', 'route' => 'schoolowner.leaves', 'icon' => 'bi-exclamation-square'],
                ['name' => 'Coupons', 'route' => 'schoolowner.coupons', 'icon' => 'bi-ticket-perforated'],
                ['name' => 'Inquiries', 'route' => 'schoolowner.inquiries', 'icon' => 'bi-envelope'],
            ];
        @endphp

        <!-- Nav items scrollable container -->
        <nav class="flex-1 flex flex-col space-y-2 text-sm font-semibold select-none scrollbar-hide overflow-y-auto">
            @foreach ($navItems as $item)
                @php
                    $isActive = $currentRoute === $item['route'];
                @endphp
                <a href="{{ route($item['route']) }}"
                    class="flex items-center space-x-3 px-3 py-2 rounded-md
                    {{ $isActive ? 'bg-black text-white' : 'text-gray-900 hover:bg-black hover:text-white' }} transition-colors duration-200">
                    <i class="bi {{ $item['icon'] }} text-lg {{ $isActive ? 'text-white' : '' }}"></i>
                    <span>{{ $item['name'] }}</span>
                </a>
            @endforeach
        </nav>

        <!-- Logout fixed at bottom -->
        <form id="logout-form" action="{{ route('logout.perform') }}" method="POST" class="flex-shrink-0 mt-5">
            @csrf
            <button type="submit"
                class="flex items-center space-x-3 px-3 py-2 rounded-md text-gray-900 hover:bg-black hover:text-white transition-colors duration-200 w-full">
                <i class="bi bi-box-arrow-right text-lg"></i>
                <span>Logout</span>
            </button>
        </form>
    </aside>

    <!-- Main content -->
    <div class="flex-1 flex flex-col min-h-screen">
        <!-- Header -->
        <header
            class="flex items-center justify-between px-6 py-4 fixed top-0 left-0 right-0 z-20 border-b border-gray-200
            bg-white shadow-sm">

            <div class="flex items-center space-x-4">
                <!-- Hamburger -->
                <button id="sidebarToggle" aria-label="Toggle sidebar"
                    class="md:hidden text-gray-600 hover:text-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 rounded font-bold text-xl leading-none select-none">
                    <i class="bi bi-list"></i>
                </button>
                <h1 class="text-xl font-semibold text-gray-800 select-none">GOFTECH</h1>
            </div>

            <div class="flex items-center space-x-4 relative">

                <!-- Notification bell -->
                <button id="notificationToggle" aria-label="Toggle notifications"
                    class="relative bg-black text-white hover:text-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 rounded px-3 py-2 rounded-full cursor-pointer">
                    <i class="bi bi-bell text-xl"></i>
                    <!-- Red dot -->
                    <span id="notificationDot"
                        class="absolute top-3 right-3 h-2 w-2 rounded-md bg-red-600 hidden"></span>
                </button>

                <!-- Profile container -->
                <div class="flex items-center bg-black rounded-md px-4 py-2 cursor-pointer select-none max-w-xs ml-4">
                    <div class="flex flex-col items-end min-w-0 mr-3">
                        <span class="text-white font-semibold truncate">
                            {{ auth()->user()->name ?? 'Goftech' }}
                        </span>
                        <span class="text-gray-300 text-sm truncate">
                            {{ ucfirst(auth()->user()->role ?? '') }}
                        </span>
                    </div>
                    <div class="w-8 h-8 rounded-full overflow-hidden mr-4 flex-shrink-0">
                        @if (auth()->user() && auth()->user()->profile_image)
                            <img src="{{ asset('storage/' . auth()->user()->profile_image) }}" alt="Profile"
                                class="w-full h-full object-cover" />
                        @else
                            <div
                                class="w-full h-full bg-indigo-600 flex items-center justify-center font-bold text-white text-sm">
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


            </div>

        </header>

        <!-- Notification Dropdown (moved outside header) -->
        <div id="notificationDropdown" class="hidden">
            <div class="p-4 border-b border-gray-200 font-semibold text-gray-700">
                Notifications
            </div>
            <ul id="notificationList" class="divide-y divide-gray-100 max-h-64 overflow-y-auto px-4">
                <!-- Notifications will be inserted here dynamically -->
            </ul>
            <div class="p-2 text-center text-gray-500 text-sm italic" id="noNotifications" style="display:none;">
                No new notifications
            </div>
        </div>

        <main class="flex-1 p-6 pt-20 overflow-auto scrollbar-hide">
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
    </script>

    <script>
        const notificationToggle = document.getElementById('notificationToggle');
        const notificationDropdown = document.getElementById('notificationDropdown');
        const notificationDot = document.getElementById('notificationDot');
        const notificationList = document.getElementById('notificationList');
        const noNotifications = document.getElementById('noNotifications');

        // Dummy notifications data (replace with your dynamic data)
        const notifications = [{
                id: 1,
                text: "New subscription request received.",
                time: "2 mins ago"
            },
            {
                id: 2,
                text: "School profile updated successfully.",
                time: "1 hour ago"
            },
            {
                id: 3,
                text: "New user registered.",
                time: "Yesterday"
            }
        ];

        // Function to render notifications
        function renderNotifications() {
            notificationList.innerHTML = '';
            if (notifications.length === 0) {
                noNotifications.style.display = 'block';
                notificationDot.classList.add('hidden');
                return;
            }
            noNotifications.style.display = 'none';
            notificationDot.classList.remove('hidden');

            notifications.forEach(notification => {
                const li = document.createElement('li');
                li.className = 'px-4 py-3 hover:bg-gray-50 cursor-pointer';

                li.innerHTML = `
                    <p class="text-gray-700">${notification.text}</p>
                    <span class="text-xs text-gray-400">${notification.time}</span>
                `;

                // You can add click event to mark as read or navigate
                notificationList.appendChild(li);
            });
        }

        // Initial render and show red dot if notifications exist
        renderNotifications();

        // Toggle dropdown visibility
        notificationToggle.addEventListener('click', (e) => {
            e.stopPropagation();
            if (notificationDropdown.style.display === 'block') {
                notificationDropdown.style.display = 'none';
            } else {
                notificationDropdown.style.display = 'block';
                notificationDot.classList.add('hidden'); // mark as read
            }
        });

        // Click outside closes dropdown
        document.addEventListener('click', () => {
            notificationDropdown.style.display = 'none';
        });

        // Prevent click inside dropdown from closing it
        notificationDropdown.addEventListener('click', e => e.stopPropagation());


        document.addEventListener('DOMContentLoaded', function() {
            const globalLoader = document.getElementById('globalLoader');

            function showLoader() {
                globalLoader.classList.remove('hidden');
            }

            function hideLoader() {
                globalLoader.classList.add('hidden');
            }

            // Show loader on all form submissions (add, update, delete, search)
            document.querySelectorAll('form').forEach(form => {
                form.addEventListener('submit', function() {
                    showLoader();
                });
            });
            document.querySelectorAll('a[href]').forEach(link => {
                const href = link.getAttribute('href');
                // Skip if href is empty, anchor link, or javascript void
                if (!href || href.startsWith('#') || href.startsWith('javascript:')) return;

                // Skip if link opens in new tab or download
                if (link.target === '_blank' || link.hasAttribute('download')) return;

                // Check if link is external (basic check)
                if (href.startsWith('http') && !href.includes(window.location.hostname)) return;

                link.addEventListener('click', function() {
                    showLoader();
                });
            });

            // Optional: Show loader on page unload (fallback for browser navs)
            window.addEventListener('beforeunload', function() {
                showLoader();
            });

            // Optional: Hide loader after full page load (to ensure it's hidden)
            window.addEventListener('load', () => {
                hideLoader();
            });
        });
    </script>
</body>

</html>
