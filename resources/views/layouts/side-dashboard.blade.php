<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>EatsHere | Admin Dashboard</title>

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>

<body class="bg-gray-100 min-h-screen  text-gray-300 flex">

    <!-- Sidebar -->
    <aside id="sidebar"
        class="sidebar fixed md:static inset-y-0 left-0 w-65 bg-[#1a1a1a] text-white p-10 space-y-6 transform -translate-x-full md:translate-x-0 z-2">

        <!-- Logo -->
        <div class=" p-4 rounded-lg inline-block">
            <img src="{{ asset('storage/eatsherelogo.jpg') }}"
                alt="EatsHere Logo"
                class="w-32 mx-auto object-contain">
        </div>


        <!-- Navigation -->
        <nav id="navLinks" class="flex flex-col space-y-2 text-sm">
            <a href="{{ url('/admin/dashboard') }}" class="nav-item flex items-center gap-3 hover:bg-[#E63946] px-4 py-2 rounded transition">Dashboard</a>
            <a href="{{ url('/admin/revenue') }}" class="nav-item flex items-center gap-3 hover:bg-[#E63946] px-4 py-2 rounded transition">Revenue</a>
            <a href="{{ url('/admin/order') }}" class="nav-item flex items-center gap-3 hover:bg-[#E63946] px-4 py-2 rounded transition">Orders</a>
            <a href="{{ url('/admin/inventory') }}" class="nav-item flex items-center gap-3 hover:bg-[#E63946] px-4 py-2 rounded transition">Inventory</a>
            <a href="{{ url('/admin/item') }}" class="nav-item flex items-center gap-3 hover:bg-[#E63946] px-4 py-2 rounded transition">Menu Items</a>
            <a href="{{ url('/admin/customer') }}" class="nav-item flex items-center gap-3 hover:bg-[#E63946] px-4 py-2 rounded transition">Customers</a>
        </nav>


       


        <!-- Sign Out Button -->
        <div class="pt-6 mt-auto border-t border-gray-700 text-center">
            <form action="{{url ('/admin/login')}}" method="">
                
                <button type="submit" class="signout-btn w-full">Sign Out</button>
            </form>
        </div>

        <!-- Footer -->
        <div class="text-center text-xs text-gray-500 mt-4">
            <p>&copy; 2025 EatsHere Diner</p>
        </div>
    </aside>

    <!-- Overlay for mobile -->
    <div id="overlay" class="fixed inset-0 bg-black/50 hidden md:hidden z-40" onclick="toggleSidebar()"></div>

    <!-- Main Content -->
    <main class="flex  bg-gradient-to-br from-gray-50 to-gray-400 transition-all duration-300">
        <!-- Top Bar (Mobile Only) -->
        <div class="flex items-center justify-between mb-8 md:hidden">
            <button onclick="toggleSidebar()" class="text-[#E63946] text-2xl focus:outline-none">☰</button>
            <h1 class="text-xl font-bold text-gray-800">Admin Dashboard</h1>
        </div>
    </main>

    <script>
        const sidebar = document.getElementById("sidebar");
        const overlay = document.getElementById("overlay");
        const navItems = document.querySelectorAll(".nav-item");

        function toggleSidebar() {
            sidebar.classList.toggle("-translate-x-full");
            overlay.classList.toggle("hidden");
        }

        // Active link highlight
        navItems.forEach((item) => {
            item.addEventListener("click", () => {
                navItems.forEach((i) => i.classList.remove("active-link"));
                item.classList.add("active-link");
            });
        });
    </script>
</html>