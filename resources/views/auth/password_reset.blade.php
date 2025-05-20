<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Forgot Password - GOFTECH</title>
    @vite('resources/css/app.css')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
</head>

<body class="bg-gradient-to-br from-indigo-50 to-purple-50 flex items-center justify-center min-h-screen font-sans">
    <div class="max-w-md w-full m-4">
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden transition-all duration-300 transform hover:shadow-2xl">

            <div class="p-8">
                <div class="flex flex-col items-center mb-4">
                    <div class="h-16 w-16 rounded-full bg-indigo-100 flex items-center justify-center mb-4">
                        <img src="{{ asset('images/GOFTECH.png') }}" alt="Goftech Logo" />
                    </div>
                    <h3 class="text-xl font-medium text-gray-800">Forgot Password</h3>
                    <p class="text-gray-500 mt-1">Enter your email to reset your password</p>
                </div>

                <form method="POST" action="{{ route('password_reset.send') }}" class="space-y-3">
                    @csrf

                    <div class="space-y-2">
                        <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                        <div class="relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="bi bi-envelope text-gray-400"></i>
                            </div>
                            <input type="email" id="email" name="email" required
                                class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500"
                                placeholder="you@example.com" />
                        </div>
                    </div>

                    <div>
                        <button type="submit"
                            class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-base font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Send Password Reset Link
                        </button>
                    </div>
                </form>

                <div class="my-3 flex items-center">
                    <div class="flex-grow border-t border-gray-200"></div>
                    <span class="flex-shrink mx-4 text-gray-400">or go back to</span>
                    <div class="flex-grow border-t border-gray-200"></div>
                </div>

                <p class="text-center mt-4 text-gray-600 text-sm">
                    <a href="{{ route('login.show') }}" class="text-indigo-600 hover:underline font-semibold">Sign In</a>
                </p>
            </div>

        </div>

        <p class="text-center mt-6 text-gray-500 text-sm">
            © 2025 GOFTECH. All rights reserved.
        </p>
    </div>
</body>

</html>
