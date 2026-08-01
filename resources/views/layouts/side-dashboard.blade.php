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

<body class="bg-gray-100 min-h-screen text-gray-300 flex">

    <!-- Sidebar -->
    <aside id="sidebar"
        class="sidebar fixed md:static inset-y-0 left-0 w-64 bg-[#1a1a1a] text-white p-6 space-y-6 transform -translate-x-full md:translate-x-0 z-50 flex flex-col justify-between transition-transform duration-300">

        <div class="space-y-6">
            <!-- Logo -->
            <div class="p-2 rounded-lg text-center">
                <img src="{{ asset('storage/eatsherelogo.jpg') }}" alt="EatsHere Logo"
                    class="w-32 mx-auto object-contain rounded-full shadow-md">
            </div>

            <!-- Navigation -->
            <nav id="navLinks" class="flex flex-col space-y-2 text-sm">
                <a href="{{ route('admin.dashboard') }}"
                    class="nav-item flex items-center gap-3 hover:bg-[#E63946] px-4 py-2.5 rounded-xl transition font-medium {{ request()->routeIs('admin.dashboard') ? 'bg-[#E63946]' : '' }}">
                    Dashboard
                </a>
                <a href="{{ route('admin.revenue') }}"
                    class="nav-item flex items-center gap-3 hover:bg-[#E63946] px-4 py-2.5 rounded-xl transition font-medium {{ request()->routeIs('admin.revenue') ? 'bg-[#E63946]' : '' }}">
                    Revenue
                </a>
                <a href="{{ route('admin.order') }}"
                    class="nav-item flex items-center gap-3 hover:bg-[#E63946] px-4 py-2.5 rounded-xl transition font-medium {{ request()->routeIs('admin.order') ? 'bg-[#E63946]' : '' }}">
                    Orders
                </a>
                <a href="{{ route('admin.inventory') }}"
                    class="nav-item flex items-center gap-3 hover:bg-[#E63946] px-4 py-2.5 rounded-xl transition font-medium {{ request()->routeIs('admin.inventory') ? 'bg-[#E63946]' : '' }}">
                    Inventory
                </a>
                <a href="{{ route('admin.item') }}"
                    class="nav-item flex items-center gap-3 hover:bg-[#E63946] px-4 py-2.5 rounded-xl transition font-medium {{ request()->routeIs('admin.item') ? 'bg-[#E63946]' : '' }}">
                    Menu Items
                </a>
                <a href="{{ route('admin.customer') }}"
                    class="nav-item flex items-center gap-3 hover:bg-[#E63946] px-4 py-2.5 rounded-xl transition font-medium {{ request()->routeIs('admin.customer') ? 'bg-[#E63946]' : '' }}">
                    Customers
                </a>
            </nav>
        </div>

        <!-- Footer & Sign Out -->
        <div>
            <!-- FIXED SIGN OUT FORM: Proper POST method + CSRF + Logout Route -->
            <div class="pt-4 border-t border-gray-700 text-center">
                <form action="{{ route('admin.logout') }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-2.5 rounded-xl transition shadow-md active:scale-95 cursor-pointer">
                        Sign Out 🚪
                    </button>
                </form>
            </div>

            <div class="text-center text-xs text-gray-500 mt-4">
                <p>&copy; 2026 EatsHere Diner</p>
            </div>
        </div>
    </aside>

    <!-- Overlay for mobile -->
    <div id="overlay" class="fixed inset-0 bg-black/50 hidden md:hidden z-40" onclick="toggleSidebar()"></div>

    <!-- Main Content -->
    <main class="flex-1 bg-gradient-to-br from-gray-50 to-gray-200 transition-all duration-300 p-8 text-gray-800">
        <!-- Top Bar (Mobile Only) -->
        <div class="flex items-center justify-between mb-8 md:hidden">
            <button onclick="toggleSidebar()" class="text-[#E63946] text-2xl focus:outline-none">☰</button>
            <h1 class="text-xl font-bold text-gray-800">Admin Dashboard</h1>
        </div>

        <!-- Dashboard Content Here -->
    </main>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById("sidebar");
            const overlay = document.getElementById("overlay");
            sidebar.classList.toggle("-translate-x-full");
            overlay.classList.toggle("hidden");
        }
    </script>
</body>

</html>