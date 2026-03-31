<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EatsHere | Admin Access</title>

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>

<body class="bg-gradient-to-br from-gray-900 via-gray-800 to-black flex items-center justify-center min-h-screen">

    <div class="relative bg-white/10 backdrop-blur-md border border-gray-700 rounded-2xl shadow-2xl w-full max-w-md text-white p-8">

        <!-- Logo -->
        <div class="text-center mb-6">
            <img src="{{ asset('storage/eatsherelogo.jpg') }}" alt="EatsHere Logo"
                class="w-24 mx-auto mb-2 drop-shadow-lg object-contain">
            <p class="text-gray-400 text-sm">Admin Portal</p>
        </div>

        <!-- Tabs -->
        <div class="flex justify-around mb-6 border-b border-gray-700">
            <button id="loginTab"
                class="py-2 font-semibold text-[#E63946] border-b-2 border-[#E63946] transition">Login</button>
            <button id="registerTab"
                class="py-2 font-semibold text-gray-400 hover:text-[#E63946] transition">Register</button>
        </div>

        <!-- LOGIN FORM -->
        <form id="loginForm" action="{{ route('login.store') }}" method="POST" class="space-y-5">
            @csrf

            <div>
                <label for="email" class="block text-sm font-semibold text-gray-300 mb-1">Email</label>
                <input type="email" id="email" name="email" required
                    class="w-full px-4 py-2 rounded-lg bg-gray-800 text-white border border-gray-600 focus:ring-2 focus:ring-[#E63946] focus:outline-none">
            </div>

            <div>
                <label for="password" class="block text-sm font-semibold text-gray-300 mb-1">Password</label>
                <input type="password" id="password" name="password" required
                    class="w-full px-4 py-2 rounded-lg bg-gray-800 text-white border border-gray-600 focus:ring-2 focus:ring-[#E63946] focus:outline-none">
            </div>

            <div class="flex items-center justify-between text-sm">
                <label class="flex items-center space-x-2">
                    <input type="checkbox" class="text-[#E63946] focus:ring-[#E63946] rounded">
                    <span class="text-gray-300">Remember me</span>
                </label>
                <a href="#" class="text-[#E63946] hover:underline">Forgot password?</a>
            </div>

            <button type="submit"
                class="w-full py-2 bg-[#E63946] hover:bg-[#c92e3a] text-white font-semibold rounded-lg transition duration-300">
                Log In
            </button>
        </form>

        <!-- REGISTRATION FORM -->
        <form id="registerForm" action="{{ route('register.store') }}" method="POST" class="space-y-5 hidden">
            @csrf

            <div>
                <label for="name" class="block text-sm font-semibold text-gray-300 mb-1">Full Name</label>
                <input type="text" id="name" name="name" required
                    class="w-full px-4 py-2 rounded-lg bg-gray-800 text-white border border-gray-600 focus:ring-2 focus:ring-[#E63946] focus:outline-none">
            </div>

            <div>
                <label for="email_reg" class="block text-sm font-semibold text-gray-300 mb-1">Email</label>
                <input type="email" id="email_reg" name="email" required
                    class="w-full px-4 py-2 rounded-lg bg-gray-800 text-white border border-gray-600 focus:ring-2 focus:ring-[#E63946] focus:outline-none">
            </div>

            <div>
                <label for="password_reg" class="block text-sm font-semibold text-gray-300 mb-1">Password</label>
                <input type="password" id="password_reg" name="password" required
                    class="w-full px-4 py-2 rounded-lg bg-gray-800 text-white border border-gray-600 focus:ring-2 focus:ring-[#E63946] focus:outline-none">
            </div>

            <div>
                <label for="confirm_password" class="block text-sm font-semibold text-gray-300 mb-1">Confirm Password</label>
                <input type="password" id="confirm_password" name="password_confirmation" required
                    class="w-full px-4 py-2 rounded-lg bg-gray-800 text-white border border-gray-600 focus:ring-2 focus:ring-[#E63946] focus:outline-none">
            </div>

            <button type="submit"
                class="w-full py-2 bg-[#E63946] hover:bg-[#c92e3a] text-white font-semibold rounded-lg transition duration-300">
                Register
            </button>
        </form>

        <!-- Footer -->
        <p class="text-center text-gray-400 text-xs mt-6">
            &copy; 2025 EatsHere Diner. All rights reserved.
        </p>
    </div>

    <script>
        const loginTab = document.getElementById('loginTab');
        const registerTab = document.getElementById('registerTab');
        const loginForm = document.getElementById('loginForm');
        const registerForm = document.getElementById('registerForm');

        loginTab.addEventListener('click', () => {
            loginForm.classList.remove('hidden');
            registerForm.classList.add('hidden');
            loginTab.classList.add('text-[#E63946]', 'border-b-2', 'border-[#E63946]');
            registerTab.classList.remove('text-[#E63946]', 'border-b-2', 'border-[#E63946]');
            registerTab.classList.add('text-gray-400');
        });

        registerTab.addEventListener('click', () => {
            registerForm.classList.remove('hidden');
            loginForm.classList.add('hidden');
            registerTab.classList.add('text-[#E63946]', 'border-b-2', 'border-[#E63946]');
            loginTab.classList.remove('text-[#E63946]', 'border-b-2', 'border-[#E63946]');
            loginTab.classList.add('text-gray-400');
        });
    </script>
</body>
@if (session('success'))
<div class="mb-4 bg-green-500/20 border border-green-400 text-green-300 px-4 py-2 rounded-lg text-sm">
    {{ session('success') }}
</div>
@endif

@if (session('error'))
<div class="mb-4 bg-red-500/20 border border-red-400 text-red-300 px-4 py-2 rounded-lg text-sm">
    {{ session('error') }}
</div>
@endif

@if ($errors->any())
<div class="mb-4 bg-red-500/20 border border-red-400 text-red-300 px-4 py-2 rounded-lg text-sm">
    <ul class="list-disc ml-5">
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif


</html>