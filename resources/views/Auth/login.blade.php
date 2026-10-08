<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign in | Horizon-LAB</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <main class="bg-white shadow-md rounded-lg w-full max-w-md p-8">
        <h1 class="text-2xl font-bold text-gray-800 mb-2 text-center">Sign in to Horizon-LAB</h1>
        <p class="text-gray-600 text-center mb-6">Choose your account type.</p>

        <div class="grid gap-3">
            <a href="{{ route('facility.login') }}" class="block text-center bg-indigo-600 text-white py-3 rounded-md hover:bg-indigo-700 transition">
                Healthcare facility
            </a>
            <a href="{{ route('engineer.login') }}" class="block text-center bg-gray-800 text-white py-3 rounded-md hover:bg-gray-900 transition">
                Engineer
            </a>
        </div>

        <p class="text-sm text-gray-600 text-center mt-6">
            Need an account?
            <a href="{{ route('register') }}" class="text-indigo-600 hover:underline">Register a healthcare facility</a>
            <span class="mx-1">or</span>
            <a href="{{ route('engineer-register') }}" class="text-indigo-600 hover:underline">join as an engineer</a>
        </p>
    </main>
</body>
</html>