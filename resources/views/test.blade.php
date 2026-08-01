@include('layouts.header')

@vite(['resources/css/app.css', 'resources/js/app.js'])

<!-- SUCCESS BANNER TOAST (Hidden by default) -->
<div id="successBanner"
    class="fixed top-5 right-5 z-50 bg-green-600 text-white px-6 py-4 rounded-2xl shadow-2xl flex items-center gap-3 transform translate-x-full opacity-0 transition-all duration-300 ease-in-out pointer-events-none">
    <span class="text-2xl">✅</span>
    <div>
        <p class="font-bold text-base" id="bannerMessage">Item added to cart!</p>
        <p class="text-xs text-green-100">Check your floating cart to review.</p>
    </div>
</div>

<script>
    function showCategory(category) {
        // Show loading screen with fade-in
        const loadingScreen = document.getElementById('loadingScreen');
        loadingScreen.classList.remove('hidden');
        loadingScreen.classList.add('flex');

        // Wait before showing products
        setTimeout(() => {
            // Hide loading
            loadingScreen.classList.remove('flex');
            loadingScreen.classList.add('hidden');

            // Fade out category selection smoothly
            const catSelection = document.getElementById('category-selection');
            catSelection.classList.add('opacity-0', 'scale-95', 'transition-all', 'duration-100');

            setTimeout(() => {
                catSelection.classList.add('hidden');
                catSelection.classList.remove('opacity-0', 'scale-95');
            }, 100);

            // Show back button with animation
            const backButton = document.getElementById('backButton');
            backButton.classList.remove('hidden');
            backButton.classList.add('flex', 'animate-fade-in');

            // Hide every category section
            document.querySelectorAll('.menu-section').forEach(section => {
                section.classList.add('hidden');
                section.classList.remove('animate-fade-in-up');
            });

            // Show selected category with a smooth slide-up animation
            const targetSection = document.getElementById(category);
            if (targetSection) {
                targetSection.classList.remove('hidden');
                targetSection.classList.add('animate-fade-in-up');
            }

        }, 500);
    }

    function goBack() {
        // Hide menu categories
        document.querySelectorAll('.menu-section').forEach(section => {
            section.classList.add('hidden');
            section.classList.remove('animate-fade-in-up');
        });

        // Hide Back button
        const backButton = document.getElementById('backButton');
        backButton.classList.remove('flex', 'animate-fade-in');
        backButton.classList.add('hidden');

        // Show category selection with smooth appearance
        const catSelection = document.getElementById('category-selection');
        catSelection.classList.remove('hidden');
        catSelection.classList.add('animate-fade-in');
    }

    // AJAX ADD TO CART HANDLER
    function handleAddToCart(event, itemName) {
        event.preventDefault(); // Stop normal form submit page refresh

        const form = event.target;
        const formData = new FormData(form);

        fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                // Update Floating Cart Badge Count dynamically
                const cartBadge = document.getElementById('cartBadgeCount');
                if (cartBadge && data.cart_count !== undefined) {
                    cartBadge.innerText = data.cart_count;
                }

                // Trigger Success Banner
                showSuccessBanner(itemName + " added to cart!");
            })
            .catch(error => {
                showSuccessBanner(itemName + " added to cart!");
            });
    }

    function showSuccessBanner(message) {
        const banner = document.getElementById('successBanner');
        const bannerMessage = document.getElementById('bannerMessage');

        bannerMessage.innerText = message;

        banner.classList.remove('translate-x-full', 'opacity-0');
        banner.classList.add('translate-x-0', 'opacity-100');

        setTimeout(() => {
            banner.classList.remove('translate-x-0', 'opacity-100');
            banner.classList.add('translate-x-full', 'opacity-0');
        }, 3000);
    }
</script>

<style>
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }

    .animate-fade-in-up {
        animation: fadeInUp 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }

    .animate-fade-in {
        animation: fadeIn 0.2s ease-in-out forwards;
    }
</style>

<body class="bg-gray-100 selection:bg-red-500 selection:text-white">

    <!-- Loading Screen -->
    <div id="loadingScreen"
        class="fixed inset-0 bg-white/80 backdrop-blur-md flex flex-col justify-center items-center hidden z-50 transition-opacity duration-300">
        <div class="relative flex items-center justify-center">
            <div class="w-20 h-20 border-[6px] border-red-200 rounded-full"></div>
            <div class="absolute w-20 h-20 border-[6px] border-red-600 border-t-transparent rounded-full animate-spin"></div>
        </div>
        <h2 class="mt-6 text-2xl font-bold text-gray-800 tracking-wide animate-pulse">
            Loading Menu...
        </h2>
        <p class="text-gray-500 text-sm mt-1">
            Getting things ready for you 🍽️
        </p>
    </div>

    <div class="max-w-7xl mx-auto py-10 px-5">

        <!-- CATEGORY SELECTION -->
        <div id="category-selection" class="transition-all duration-300">
            <h2 class="text-2xl font-bold text-center mb-8 text-gray-700">
                Select a Menu Category
            </h2>

            <div class="flex flex-col max-w-md mx-auto gap-4">
                <button onclick="showCategory('breakfast')"
                    class="bg-orange-500 hover:bg-orange-600 text-white rounded-2xl py-5 text-xl font-semibold shadow-lg hover:shadow-2xl transform hover:-translate-y-1 active:translate-y-0 transition-all duration-300 flex items-center justify-start px-8 group w-full cursor-pointer">
                    <span class="text-3xl mr-4 group-hover:scale-125 transition-transform duration-300">🍳</span>
                    Breakfast
                </button>

                <button onclick="showCategory('lunch')"
                    class="bg-green-500 hover:bg-green-600 text-white rounded-2xl py-5 text-xl font-semibold shadow-lg hover:shadow-2xl transform hover:-translate-y-1 active:translate-y-0 transition-all duration-300 flex items-center justify-start px-8 group w-full cursor-pointer">
                    <span class="text-3xl mr-4 group-hover:scale-125 transition-transform duration-300">🍛</span>
                    Lunch and Dinner
                </button>

                <button onclick="showCategory('dessert')"
                    class="bg-pink-500 hover:bg-pink-600 text-white rounded-2xl py-5 text-xl font-semibold shadow-lg hover:shadow-2xl transform hover:-translate-y-1 active:translate-y-0 transition-all duration-300 flex items-center justify-start px-8 group w-full cursor-pointer">
                    <span class="text-3xl mr-4 group-hover:scale-125 transition-transform duration-300">🍰</span>
                    Dessert
                </button>

                <button onclick="showCategory('extras')"
                    class="bg-gray-700 hover:bg-gray-800 text-white rounded-2xl py-5 text-xl font-semibold shadow-lg hover:shadow-2xl transform hover:-translate-y-1 active:translate-y-0 transition-all duration-300 flex items-center justify-start px-8 group w-full cursor-pointer">
                    <span class="text-3xl mr-4 group-hover:scale-125 transition-transform duration-300">🥤</span>
                    Extras
                </button>
            </div>
        </div>

        <!-- BACK BUTTON -->
        <div id="backButton" class="hidden justify-end mb-6">
            <button onclick="goBack()"
                class="bg-gray-900 hover:bg-red-600 text-white px-5 py-2.5 rounded-xl shadow-md hover:shadow-lg transition-all duration-300 flex items-center gap-2 font-medium transform hover:-translate-x-1 cursor-pointer">
                <span>←</span> Back to Categories
            </button>
        </div>

        <!-- ================= BREAKFAST ================= -->
        <div id="breakfast" class="menu-section hidden">
            <h2 class="text-3xl font-extrabold text-orange-600 mb-6 flex items-center gap-2">
                <span>🍳</span> Breakfast Silog Meals
            </h2>

            <div class="grid md:grid-cols-3 gap-6">
               @forelse(($items ?? collect())->where('category', 'Breakfast') as $item)
                    <div class="bg-white rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 p-5 transform hover:-translate-y-1 hover:bg-red-50 hover:border-red-200 border border-transparent flex flex-col justify-between">
                        <div>
                            <img src="{{ $item->image ? asset('storage/' . $item->image) : asset('storage/eatsherelogo.jpg') }}" 
                                class="rounded-xl mb-4 w-full h-48 object-cover border border-gray-100">
                            <h3 class="font-bold text-2xl text-gray-800">{{ $item->name }}</h3>
                            <p class="text-red-600 font-extrabold text-xl mt-2">₱{{ number_format($item->price, 2) }}</p>
                        </div>

                        <form action="{{ route('cart.add') }}" method="POST" onsubmit="handleAddToCart(event, '{{ $item->name }}')">
                            @csrf
                            <input type="hidden" name="item_id" value="{{ $item->id }}">
                            <input type="hidden" name="item_name" value="{{ $item->name }}">
                            <input type="hidden" name="item_price" value="{{ $item->price }}">

                            <button type="submit" class="mt-4 w-full bg-red-600 hover:bg-red-700 text-white py-2.5 rounded-xl font-semibold shadow transition-all duration-200 active:scale-95 cursor-pointer">
                                Add to Cart
                            </button>
                        </form>
                    </div>
                @empty
                    <div class="col-span-3 text-center py-12 text-gray-400">
                        No breakfast items available right now.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- ================= LUNCH & DINNER ================= -->
        <div id="lunch" class="menu-section hidden">
            <h2 class="text-3xl font-extrabold text-green-600 mb-6 flex items-center gap-3">
                <span>🍛</span> Lunch and Dinner Menu
            </h2>
            
            <div class="grid md:grid-cols-3 gap-6">
                @forelse(($items ?? collect())->where('category', 'Lunch/Dinner') as $item)
                    <div class="bg-white rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 p-5 transform hover:-translate-y-1 hover:bg-red-50 hover:border-red-200 border border-transparent flex flex-col justify-between">
                        <div>
                            <img src="{{ $item->image ? asset('storage/' . $item->image) : asset('storage/eatsherelogo.jpg') }}" 
                                class="rounded-xl mb-4 w-full h-48 object-cover border border-gray-100">
                            <h3 class="font-bold text-2xl text-gray-800">{{ $item->name }}</h3>
                            <p class="text-red-600 font-extrabold text-xl mt-2">₱{{ number_format($item->price, 2) }}</p>
                        </div>

                        <form action="{{ route('cart.add') }}" method="POST" onsubmit="handleAddToCart(event, '{{ $item->name }}')">
                            @csrf
                            <input type="hidden" name="item_id" value="{{ $item->id }}">
                            <input type="hidden" name="item_name" value="{{ $item->name }}">
                            <input type="hidden" name="item_price" value="{{ $item->price }}">

                            <button type="submit" class="mt-4 w-full bg-red-600 hover:bg-red-700 text-white py-2.5 rounded-xl font-semibold shadow transition-all duration-200 active:scale-95 cursor-pointer">
                                Add to Cart
                            </button>
                        </form>
                    </div>
                @empty
                    <div class="col-span-3 text-center py-12 text-gray-400">
                        No lunch/dinner items available right now.
                    </div>
                @endforelse
            </div>
        </div>



        <!-- ================= DESSERT ================= -->
        <div id="dessert" class="menu-section hidden">
            <h2 class="text-3xl font-extrabold text-pink-600 mb-6 flex items-center gap-3">
                <span>🍰</span> Dessert Menu
            </h2>

            <div class="grid md:grid-cols-3 gap-6">
                @forelse(($items ?? collect())->where('category', 'Desserts') as $item)
                    <div class="bg-white rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 p-5 transform hover:-translate-y-1 hover:bg-red-50 hover:border-red-200 border border-transparent flex flex-col justify-between">
                        <div>
                            <img src="{{ $item->image ? asset('storage/' . $item->image) : asset('storage/eatsherelogo.jpg') }}" 
                                class="rounded-xl mb-4 w-full h-48 object-cover border border-gray-100">
                            <h3 class="font-bold text-2xl text-gray-800">{{ $item->name }}</h3>
                            <p class="text-red-600 font-extrabold text-xl mt-2">₱{{ number_format($item->price, 2) }}</p>
                        </div>

                        <form action="{{ route('cart.add') }}" method="POST" onsubmit="handleAddToCart(event, '{{ $item->name }}')">
                            @csrf
                            <input type="hidden" name="item_id" value="{{ $item->id }}">
                            <input type="hidden" name="item_name" value="{{ $item->name }}">
                            <input type="hidden" name="item_price" value="{{ $item->price }}">

                            <button type="submit" class="mt-4 w-full bg-red-600 hover:bg-red-700 text-white py-2.5 rounded-xl font-semibold shadow transition-all duration-200 active:scale-95 cursor-pointer">
                                Add to Cart
                            </button>
                        </form>
                    </div>
                @empty
                    <div class="col-span-3 text-center py-12 text-gray-400">
                        No dessert items available right now.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- ================= EXTRAS ================= -->
        <div id="extras" class="menu-section hidden">
            <h2 class="text-3xl font-extrabold text-gray-700 mb-6 flex items-center gap-3">
                <span>🥤</span> Extras Menu
            </h2>

            <div class="grid md:grid-cols-3 gap-6">
                @forelse(($items ?? collect())->where('category', 'Extras') as $item)
                    <div class="bg-white rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 p-5 transform hover:-translate-y-1 hover:bg-red-50 hover:border-red-200 border border-transparent flex flex-col justify-between">
                        <div>
                            <img src="{{ $item->image ? asset('storage/' . $item->image) : asset('storage/eatsherelogo.jpg') }}" 
                                class="rounded-xl mb-4 w-full h-48 object-cover border border-gray-100">
                            <h3 class="font-bold text-2xl text-gray-800">{{ $item->name }}</h3>
                            <p class="text-red-600 font-extrabold text-xl mt-2">₱{{ number_format($item->price, 2) }}</p>
                        </div>

                        <form action="{{ route('cart.add') }}" method="POST" onsubmit="handleAddToCart(event, '{{ $item->name }}')">
                            @csrf
                            <input type="hidden" name="item_id" value="{{ $item->id }}">
                            <input type="hidden" name="item_name" value="{{ $item->name }}">
                            <input type="hidden" name="item_price" value="{{ $item->price }}">

                            <button type="submit" class="mt-4 w-full bg-red-600 hover:bg-red-700 text-white py-2.5 rounded-xl font-semibold shadow transition-all duration-200 active:scale-95 cursor-pointer">
                                Add to Cart
                            </button>
                        </form>
                    </div>
                @empty
                    <div class="col-span-3 text-center py-12 text-gray-400">
                        No extra items available right now.
                    </div>
                @endforelse
            </div>
        </div>

    </div>

</body>

@include('layouts.footer')