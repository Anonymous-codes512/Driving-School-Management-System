<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Login - GOFTECH</title>
    @vite('resources/css/app.css')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
     <!-- Toastr CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">

</head>

<body class="bg-gradient-to-br from-indigo-50 to-purple-50 flex items-center justify-center min-h-screen font-sans">
    <div class="max-w-md w-full m-4">
        <!-- Card container -->
        <div
            class="bg-white rounded-2xl shadow-xl overflow-hidden transition-all duration-300 transform hover:shadow-2xl">
            <!-- Login Form -->
            <div class="p-8">
                <!-- Logo and welcome message -->
                <div class="flex flex-col items-center mb-4">
                    <div class="h-16 w-16 rounded-full bg-indigo-100 flex items-center justify-center mb-4">
                        <img src="{{ asset('images/GOFTECH.png') }}" alt="Goftech Logo" />
                    </div>
                    <h3 class="text-xl font-medium text-gray-800">Welcome Back</h3>
                    <p class="text-gray-500 mt-1">Enter your credentials to access your account</p>
                </div>

                <!-- Form -->
                <form id="login-form" method="POST" action="{{ route('login.perform') }}" class="space-y-3"
                    enctype="multipart/form-data">
                    @csrf
                    <!-- Email Field -->
                    <div class="space-y-2">
                        <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                        <div class="relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="bi bi-envelope text-gray-400"></i>
                            </div>
                            <input type="email" id="email" name="email"
                                class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500"
                                placeholder="you@example.com" required autofocus />
                        </div>
                        <!-- Remove error display for now -->
                    </div>

                    <!-- Password Field -->
                    <div class="space-y-2 relative">
                        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                        <div class="relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="bi bi-lock-fill text-gray-400"></i>
                            </div>
                            <input type="password" id="password" name="password"
                                class="block w-full pl-10 pr-10 py-3 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500"
                                placeholder="••••••••" required />
                            <!-- Toggle button -->
                            <button type="button" id="togglePassword"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none"
                                tabindex="-1" aria-label="Show or hide password">
                                <i class="bi bi-eye" id="toggleIcon"></i>
                            </button>
                        </div>
                        <!-- Forgot password link -->
                        <div class="flex justify-between mt-2">
                            <a href="{{ route('password_reset.show') }}"
                                class="text-sm font-medium text-indigo-600 hover:text-indigo-500">
                                Forgot password?
                            </a>
                        </div>
                    </div>

                    <!-- Remember Me -->
                    {{-- <div class="flex items-center">
                        <input type="checkbox" id="remember" name="remember"
                            class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded" />
                        <label for="remember" class="ml-2 block text-sm text-gray-700">
                            Remember me
                        </label>
                    </div> --}}

                    <!-- Submit Button -->
                    <div>
                        <button type="submit"
                            class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-base font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Sign in
                        </button>
                    </div>
                </form>

                <!-- Register Link -->
                <p class="text-center mt-6 text-gray-600 text-sm">
                    Don't have an account?
                    <a href="{{ route('register.show') }}" class="text-indigo-600 hover:underline font-semibold">Sign
                        Up</a>
                </p>
            </div>
        </div>

        <!-- Footer -->
        <p class="text-center mt-3 text-gray-500 text-sm">
            © 2025 GOFTECH. All rights reserved.
        </p>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const togglePassword = document.getElementById('togglePassword');
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');

            togglePassword.addEventListener('click', () => {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);

                // Toggle icon between eye and eye-slash
                if (type === 'password') {
                    toggleIcon.classList.remove('bi-eye-slash');
                    toggleIcon.classList.add('bi-eye');
                } else {
                    toggleIcon.classList.remove('bi-eye');
                    toggleIcon.classList.add('bi-eye-slash');
                }
            });
        });
    </script>
    <!-- jQuery (Toastr depends on jQuery) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
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
</body>

</html>
