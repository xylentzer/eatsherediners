<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EatsHere | Admin Portal</title>

    @vite(['resources/css/app.css','resources/js/app.js'])
</head>

<body class="bg-gradient-to-br from-gray-900 via-gray-800 to-black flex items-center justify-center min-h-screen">

<div class="bg-white/10 backdrop-blur-md border border-gray-700 rounded-2xl shadow-2xl w-full max-w-md p-8 text-white">

    <!-- Logo -->
    <div class="text-center mb-6">
        <img src="{{ asset('storage/eatsherelogo.jpg') }}"
             class="w-24 mx-auto mb-3 rounded-full shadow-md object-cover"
             alt="EatsHere Logo">

        <h2 class="text-xl font-bold tracking-tight">
            Admin Portal
        </h2>
    </div>

    <!-- Success Banner Message -->
    @if(session('success'))
        <div id="successBanner" class="mb-4 rounded-xl bg-green-500/20 border border-green-500 p-4 text-green-300 text-sm flex justify-between items-center shadow-lg">
            <div>
                <span class="font-bold">🎉 Success!</span> {{ session('success') }}
            </div>
            <button onclick="document.getElementById('successBanner').remove()" type="button" class="text-green-300 hover:text-white font-bold ml-2 text-lg">
                &times;
            </button>
        </div>
    @endif

    <!-- Error Session Message -->
    @if(session('error'))
        <div class="mb-4 rounded-xl bg-red-500/20 border border-red-500 p-3 text-red-300 text-sm">
            {{ session('error') }}
        </div>
    @endif

    <!-- Validation Errors -->
    @if($errors->any())
        <div class="mb-4 rounded-xl bg-red-500/20 border border-red-500 p-3">
            <ul class="list-disc ml-5 text-red-300 text-sm space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Tabs -->
    <div class="flex border-b border-gray-700 mb-6">
        <button id="loginTab"
            type="button"
            class="flex-1 py-3 border-b-2 border-red-500 text-red-500 font-semibold transition-all">
            Login
        </button>

        <button id="registerTab"
            type="button"
            class="flex-1 py-3 border-b-2 border-transparent text-gray-400 font-semibold transition-all hover:text-white">
            Register
        </button>
    </div>

    <!-- LOGIN FORM -->
    <form id="loginForm" method="POST" action="{{ Route::has('admin.login.submit') ? route('admin.login.submit') : (Route::has('login') ? route('login') : '#') }}" class="space-y-5">
        @csrf

        <div>
            <label class="block text-sm font-medium mb-1">Email Address</label>
            <input type="email"
                name="email"
                value="{{ old('email') }}"
                required
                placeholder="admin@eatshere.com"
                class="w-full px-4 py-2.5 rounded-xl bg-gray-800/80 border border-gray-600 text-white focus:ring-2 focus:ring-red-500 focus:outline-none transition">
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Password</label>
            <input type="password"
                name="password"
                required
                placeholder="••••••••"
                class="w-full px-4 py-2.5 rounded-xl bg-gray-800/80 border border-gray-600 text-white focus:ring-2 focus:ring-red-500 focus:outline-none transition">
        </div>

        <div class="flex justify-between items-center text-sm">
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="remember" class="rounded bg-gray-800 border-gray-600 text-red-600 focus:ring-red-500">
                <span>Remember me</span>
            </label>

            @if(Route::has('password.request'))
                <a href="{{ route('password.request') }}"
                   class="text-red-400 hover:text-red-300 hover:underline transition">
                    Forgot Password?
                </a>
            @endif
        </div>

        <button type="submit"
            class="w-full py-3 rounded-xl bg-red-600 hover:bg-red-700 font-bold transition shadow-lg active:scale-95">
            Log In
        </button>
    </form>

    <!-- REGISTER FORM (Points directly to admin.register.submit) -->
    <form id="registerForm" method="POST" action="{{ Route::has('admin.register.submit') ? route('admin.register.submit') : (Route::has('register') ? route('register') : '#') }}" class="space-y-5 hidden">
        @csrf

        <div>
            <label class="block text-sm font-medium mb-1">Full Name</label>
            <input type="text"
                name="name"
                value="{{ old('name') }}"
                required
                placeholder="Juan Dela Cruz"
                class="w-full px-4 py-2.5 rounded-xl bg-gray-800/80 border border-gray-600 text-white focus:ring-2 focus:ring-red-500 focus:outline-none transition">
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Email Address</label>
            <input type="email"
                name="email"
                value="{{ old('email') }}"
                required
                placeholder="admin@eatshere.com"
                class="w-full px-4 py-2.5 rounded-xl bg-gray-800/80 border border-gray-600 text-white focus:ring-2 focus:ring-red-500 focus:outline-none transition">
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Password</label>
            <input type="password"
                name="password"
                required
                placeholder="••••••••"
                class="w-full px-4 py-2.5 rounded-xl bg-gray-800/80 border border-gray-600 text-white focus:ring-2 focus:ring-red-500 focus:outline-none transition">
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Confirm Password</label>
            <input type="password"
                name="password_confirmation"
                required
                placeholder="••••••••"
                class="w-full px-4 py-2.5 rounded-xl bg-gray-800/80 border border-gray-600 text-white focus:ring-2 focus:ring-red-500 focus:outline-none transition">
        </div>

        <button type="submit"
            class="w-full py-3 rounded-xl bg-red-600 hover:bg-red-700 font-bold transition shadow-lg active:scale-95">
            Register
        </button>
    </form>

    <div class="text-center text-gray-500 text-xs mt-8">
        © 2026 EatsHere Diner. All Rights Reserved.
    </div>

</div>

<!-- SMART TAB TOGGLE SCRIPT -->
<script>
    const loginTab = document.getElementById('loginTab');
    const registerTab = document.getElementById('registerTab');

    const loginForm = document.getElementById('loginForm');
    const registerForm = document.getElementById('registerForm');

    function showLogin() {
        loginForm.classList.remove('hidden');
        registerForm.classList.add('hidden');

        loginTab.classList.add('border-red-500', 'text-red-500');
        loginTab.classList.remove('border-transparent', 'text-gray-400');

        registerTab.classList.add('border-transparent', 'text-gray-400');
        registerTab.classList.remove('border-red-500', 'text-red-500');
    }

    function showRegister() {
        registerForm.classList.remove('hidden');
        loginForm.classList.add('hidden');

        registerTab.classList.add('border-red-500', 'text-red-500');
        registerTab.classList.remove('border-transparent', 'text-gray-400');

        loginTab.classList.add('border-transparent', 'text-gray-400');
        loginTab.classList.remove('border-red-500', 'text-red-500');
    }

    loginTab.onclick = showLogin;
    registerTab.onclick = showRegister;

    // Auto switch to Register tab if there are validation errors on registration input
    @if ($errors->has('name') || $errors->has('password_confirmation'))
        showRegister();
    @endif
</script>

</body>
</html>