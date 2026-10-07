<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin sign in | Horizon-LAB</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 min-h-screen flex items-center justify-center">
    <main class="bg-white shadow-lg rounded-xl w-full max-w-md p-8">
        <div class="flex items-center justify-center mb-6">
            <img src="{{ asset('images/logo.png') }}" alt="Horizon Lab logo" class="h-14 w-14 object-contain">
        </div>

        <h1 class="text-2xl font-bold text-gray-800 mb-2 text-center">Admin sign in</h1>
        <p class="text-gray-600 text-center mb-6">Approve engineers and assign them to hospital requests.</p>

        <form method="POST" action="{{ route('admin.login.submit') }}" class="space-y-4">
            @csrf

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus
                    class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 focus:border-indigo-500 focus:outline-none">
                @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input id="password" name="password" type="password" required
                    class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 focus:border-indigo-500 focus:outline-none">
            </div>

            <button type="submit" class="w-full bg-slate-900 text-white font-semibold py-3 rounded-md hover:bg-slate-800 transition">
                Sign in as admin
            </button>
        </form>

        <p class="text-sm text-center mt-6 text-gray-600">
            <a href="{{ route('login') }}" class="text-indigo-600 hover:underline">Back to account selection</a>
        </p>
    </main>
</body>
</html>
