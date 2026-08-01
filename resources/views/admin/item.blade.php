<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>EatsHere | Menu Items</title>

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>

<body class="bg-gray-100 min-h-screen text-gray-800 flex">

    <!-- SIDEBAR -->
    <aside id="sidebar"
        class="fixed md:static inset-y-0 left-0 w-64 bg-[#1a1a1a] text-white p-6 space-y-6 transform -translate-x-full md:translate-x-0 z-50 flex flex-col justify-between transition-transform duration-300">

        <div class="space-y-6">
            <!-- Logo -->
            <div class="p-2 text-center">
                <img src="{{ asset('storage/eatsherelogo.jpg') }}" alt="EatsHere Logo"
                    class="w-28 mx-auto object-contain rounded-full shadow-md">
            </div>

            <!-- Navigation Links -->
            <nav class="flex flex-col space-y-2 text-sm font-medium">
                <a href="{{ route('admin.dashboard') }}"
                    class="flex items-center gap-3 hover:bg-[#E63946] px-4 py-2.5 rounded-xl transition">
                    Dashboard
                </a>
                <a href="{{ route('admin.revenue') }}"
                    class="flex items-center gap-3 hover:bg-[#E63946] px-4 py-2.5 rounded-xl transition">
                    Revenue
                </a>
                <a href="{{ route('admin.order') }}"
                    class="flex items-center gap-3 hover:bg-[#E63946] px-4 py-2.5 rounded-xl transition">
                    Orders
                </a>
                <a href="{{ route('admin.inventory') }}"
                    class="flex items-center gap-3 hover:bg-[#E63946] px-4 py-2.5 rounded-xl transition">
                    Inventory
                </a>
                <a href="{{ route('admin.item') }}"
                    class="flex items-center gap-3 bg-[#E63946] px-4 py-2.5 rounded-xl transition shadow-md">
                    Menu Items
                </a>
                <a href="{{ route('admin.customer') }}"
                    class="flex items-center gap-3 hover:bg-[#E63946] px-4 py-2.5 rounded-xl transition">
                    Customers
                </a>
            </nav>
        </div>

        <!-- Sign Out -->
        <div class="pt-4 border-t border-gray-700 text-center">
            <form action="{{ route('admin.logout') }}" method="POST">
                @csrf
                <button type="submit"
                    class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-2.5 rounded-xl transition active:scale-95 cursor-pointer">
                    Sign Out 🚪
                </button>
            </form>
        </div>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="flex-1 bg-gray-50 p-8 min-h-screen">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Menu Items</h1>
                <p class="text-gray-500 text-sm">Manage your restaurant menu items and prices by category.</p>
            </div>

            <button onclick="openCreateModal()"
                class="bg-[#E63946] hover:bg-red-700 text-white font-bold px-5 py-2.5 rounded-xl shadow transition active:scale-95 cursor-pointer">
                + Add New Item
            </button>
        </div>

        <!-- Alert Notifications -->
        @if (session('success'))
            <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl text-sm">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl text-sm">
                <ul class="list-disc ml-5 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- CATEGORY TABS (Removed 'All Items') -->
        <div class="flex space-x-2 border-b border-gray-200 mb-6 pb-2 overflow-x-auto">
            <button onclick="filterCategory('Breakfast', this)"
                class="tab-btn bg-[#E63946] text-white font-semibold px-4 py-2 rounded-xl text-sm transition">
                Breakfast
            </button>
            <button onclick="filterCategory('Lunch/Dinner', this)"
                class="tab-btn bg-white hover:bg-gray-100 text-gray-600 font-semibold px-4 py-2 rounded-xl text-sm transition border border-gray-200">
                Lunch/Dinner
            </button>
            <button onclick="filterCategory('Desserts', this)"
                class="tab-btn bg-white hover:bg-gray-100 text-gray-600 font-semibold px-4 py-2 rounded-xl text-sm transition border border-gray-200">
                Desserts
            </button>
            <button onclick="filterCategory('Extras', this)"
                class="tab-btn bg-white hover:bg-gray-100 text-gray-600 font-semibold px-4 py-2 rounded-xl text-sm transition border border-gray-200">
                Extras
            </button>
        </div>

        <!-- Menu Table -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200 text-xs font-bold text-gray-500 uppercase tracking-wider">
                        <th class="py-4 px-6">Image</th>
                        <th class="py-4 px-6">Item Name</th>
                        <th class="py-4 px-6">Category</th>
                        <th class="py-4 px-6">Price</th>
                        <th class="py-4 px-6 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
                    @forelse($items as $item)
                        <tr class="item-row" data-category="{{ $item->category }}">
                            <td class="py-4 px-6">
                                <img src="{{ $item->image ? asset('storage/' . $item->image) : asset('storage/eatsherelogo.jpg') }}"
                                    class="w-12 h-12 rounded-lg object-cover border border-gray-200">
                            </td>
                            <td class="py-4 px-6 font-bold text-gray-900">{{ $item->name }}</td>
                            <td class="py-4 px-6 text-gray-500">
                                <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-xs font-semibold">
                                    {{ $item->category }}
                                </span>
                            </td>
                            <td class="py-4 px-6 font-bold text-[#E63946]">₱{{ number_format($item->price, 2) }}</td>
                            <td class="py-4 px-6 text-right space-x-2">
                                <button onclick="openEditModal({{ $item }})"
                                    class="text-blue-600 hover:underline font-semibold cursor-pointer">Edit</button>
                                
                                <form action="{{ route('admin.item.destroy', $item->id) }}" method="POST" class="inline"
                                    onsubmit="return confirm('Are you sure you want to delete this item?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline font-semibold cursor-pointer">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-gray-400">
                                No menu items found. Click <strong>+ Add New Item</strong> to get started.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </main>

    <!-- CREATE MODAL -->
    <div id="createModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50 p-4">
        <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl space-y-4">
            <h2 class="text-xl font-bold text-gray-900">Add New Menu Item</h2>
            
            <form action="{{ route('admin.item.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Item Name</label>
                    <input type="text" name="name" required placeholder="e.g. Tapsilog"
                        class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#E63946] focus:outline-none">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Category</label>
                    <select name="category" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#E63946] focus:outline-none bg-white">
                        <option value="Breakfast">Breakfast</option>
                        <option value="Lunch/Dinner">Lunch/Dinner</option>
                        <option value="Desserts">Desserts</option>
                        <option value="Extras">Extras</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Price (₱)</label>
                    <input type="number" step="0.01" name="price" required placeholder="180.00"
                        class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#E63946] focus:outline-none">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Image</label>
                    <input type="file" name="image" accept="image/*"
                        class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-red-50 file:text-[#E63946] hover:file:bg-red-100">
                </div>

                <div class="flex justify-end gap-3 pt-4">
                    <button type="button" onclick="closeCreateModal()" class="px-4 py-2 text-gray-600 hover:underline">Cancel</button>
                    <button type="submit" class="px-5 py-2 bg-[#E63946] hover:bg-red-700 text-white font-bold rounded-xl shadow">Save Item</button>
                </div>
            </form>
        </div>
    </div>

    <!-- EDIT MODAL -->
    <div id="editModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50 p-4">
        <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl space-y-4">
            <h2 class="text-xl font-bold text-gray-900">Edit Menu Item</h2>
            
            <form id="editForm" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                @method('PUT')
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Item Name</label>
                    <input type="text" id="edit_name" name="name" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#E63946] focus:outline-none">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Category</label>
                    <select id="edit_category" name="category" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#E63946] focus:outline-none bg-white">
                        <option value="Breakfast">Breakfast</option>
                        <option value="Lunch/Dinner">Lunch/Dinner</option>
                        <option value="Desserts">Desserts</option>
                        <option value="Extras">Extras</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Price (₱)</label>
                    <input type="number" step="0.01" id="edit_price" name="price" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#E63946] focus:outline-none">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">New Image (Optional)</label>
                    <input type="file" name="image" accept="image/*"
                        class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-red-50 file:text-[#E63946] hover:file:bg-red-100">
                </div>

                <div class="flex justify-end gap-3 pt-4">
                    <button type="button" onclick="closeEditModal()" class="px-4 py-2 text-gray-600 hover:underline">Cancel</button>
                    <button type="submit" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow">Update Item</button>
                </div>
            </form>
        </div>
    </div>

    <!-- SCRIPT FOR MODALS & CATEGORY FILTERING -->
    <script>
        function openCreateModal() {
            document.getElementById('createModal').classList.remove('hidden');
            document.getElementById('createModal').classList.add('flex');
        }

        function closeCreateModal() {
            document.getElementById('createModal').classList.add('hidden');
            document.getElementById('createModal').classList.remove('flex');
        }

        function openEditModal(item) {
            document.getElementById('edit_name').value = item.name;
            document.getElementById('edit_category').value = item.category;
            document.getElementById('edit_price').value = item.price;
            
            document.getElementById('editForm').action = `/admin/item/${item.id}`;

            document.getElementById('editModal').classList.remove('hidden');
            document.getElementById('editModal').classList.add('flex');
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
            document.getElementById('editModal').classList.remove('flex');
        }

        function filterCategory(category, selectedBtn) {
            const rows = document.querySelectorAll('.item-row');
            const buttons = document.querySelectorAll('.tab-btn');

            // Reset all tab button styles to unselected
            buttons.forEach(btn => {
                btn.classList.remove('bg-[#E63946]', 'text-white');
                btn.classList.add('bg-white', 'text-gray-600', 'hover:bg-gray-100', 'border', 'border-gray-200');
            });

            // Set clicked button to active red style
            selectedBtn.classList.remove('bg-white', 'text-gray-600', 'hover:bg-gray-100', 'border', 'border-gray-200');
            selectedBtn.classList.add('bg-[#E63946]', 'text-white');

            // Show matching rows, hide non-matching
            rows.forEach(row => {
                if (row.dataset.category === category) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        // Initialize table on load to show 'Breakfast' by default
        document.addEventListener('DOMContentLoaded', () => {
            const firstTab = document.querySelector('.tab-btn');
            if (firstTab) {
                filterCategory('Breakfast', firstTab);
            }
        });
    </script>

</body>
</html>