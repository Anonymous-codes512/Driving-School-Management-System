<html>
<head>
    <title>Reset Password</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-50 flex items-center justify-center min-h-screen">

    <div class="max-w-md w-full bg-white p-8 rounded-lg shadow-lg">
        <h2 class="text-2xl font-bold mb-6">Reset Password</h2>

        @if ($errors->any())
            <div class="mb-4 text-red-600">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>• {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('password_reset.perform', $token) }}">
            @csrf

            <input type="hidden" name="email" value="{{ $email }}">

            <div class="mb-4">
                <label for="password" class="block text-sm font-medium">New Password</label>
                <input type="password" name="password" id="password" required
                    class="w-full border border-gray-300 rounded p-2" />
            </div>

            <div class="mb-4">
                <label for="password_confirmation" class="block text-sm font-medium">Confirm Password</label>
                <input type="password" name="password_confirmation" id="password_confirmation" required
                    class="w-full border border-gray-300 rounded p-2" />
            </div>

            <button type="submit"
                class="w-full bg-indigo-600 text-white py-3 rounded hover:bg-indigo-700">
                Reset Password
            </button>
        </form>
    </div>

</body>
</html>
