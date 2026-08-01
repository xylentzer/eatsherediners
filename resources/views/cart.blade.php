@include('layouts.header')

@vite(['resources/css/app.css','resources/js/app.js'])

<div class="max-w-6xl mx-auto px-6 py-10">

    <div class="flex justify-between items-center mb-8">
        <h1 class="text-4xl font-bold text-red-600">
            🛒 Shopping Cart
        </h1>

        <a href="{{ route('test') }}"
            class="bg-gray-700 hover:bg-gray-800 text-white px-6 py-3 rounded-xl shadow-lg transition">
            ← Return to Menu
        </a>
    </div>

    @php
    $cart = session('cart', []);
    $grandTotal = 0;
    @endphp

    @if(count($cart))

    <div class="space-y-6">

        @foreach($cart as $id => $item)

        @php
        $subtotal = $item['price'] * $item['qty'];
        $grandTotal += $subtotal;
        @endphp

        <div class="bg-white rounded-2xl shadow-lg p-6">
            <div class="flex flex-col md:flex-row items-center justify-between gap-6">

                <!-- Image -->
                <img src="{{ !empty($item['image']) ? $item['image'] : asset('storage/default.jpg') }}"
                    alt="{{ $item['name'] }}"
                    class="w-36 h-36 rounded-xl object-cover shadow-md">

                <!-- Product Details -->
                <div class="flex-1">
                    <h2 class="text-2xl font-bold">
                        {{ $item['name'] }}
                    </h2>

                    <p class="text-red-600 text-xl font-bold mt-2">
                        ₱{{ number_format($item['price'], 2) }}
                    </p>

                    <p class="text-gray-500 mt-2">
                        Subtotal:
                        <span class="font-bold text-black">
                            ₱{{ number_format($subtotal, 2) }}
                        </span>
                    </p>
                </div>

                <!-- Quantity -->
                <div class="flex items-center gap-3">
                    <form action="{{ route('cart.decrease', $id) }}" method="POST">
                        @csrf
                        @method('PATCH')

                        <button class="w-10 h-10 bg-gray-200 hover:bg-gray-300 rounded-full text-xl flex items-center justify-center font-bold">
                            -
                        </button>
                    </form>

                    <span class="text-2xl font-bold w-8 text-center">
                        {{ $item['qty'] }}
                    </span>

                    <form action="{{ route('cart.increase', $id) }}" method="POST">
                        @csrf
                        @method('PATCH')

                        <button class="w-10 h-10 bg-red-500 hover:bg-red-600 text-white rounded-full text-xl flex items-center justify-center font-bold">
                            +
                        </button>
                    </form>
                </div>

                <!-- Remove -->
                <form action="{{ route('cart.remove', $id) }}" method="POST">
                    @csrf
                    @method('DELETE')

                    <button class="bg-red-600 hover:bg-red-700 text-white px-5 py-3 rounded-xl font-semibold transition">
                        Remove
                    </button>
                </form>

            </div>
        </div>

        @endforeach

    </div>

    <!-- TOTAL & PLACE ORDER -->
    <div class="mt-10 bg-white rounded-2xl shadow-lg p-8">

        <div class="flex justify-between text-3xl font-bold">
            <span>Total</span>
            <span class="text-red-600">
                ₱{{ number_format($grandTotal, 2) }}
            </span>
        </div>

        <!-- PLACE ORDER BUTTON CONTAINER -->
        <div class="flex justify-end gap-4 mt-8">
            <button onclick="openOrderSummaryModal()" type="button"
                class="bg-red-600 hover:bg-red-700 text-white px-8 py-3.5 rounded-xl font-bold text-lg shadow-md transition-all active:scale-95 cursor-pointer">
                Place Order
            </button>
        </div>

        <!-- MULTI-STEP ORDER MODAL -->
        <div id="orderSummaryModal" class="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm flex items-center justify-center hidden opacity-0 transition-opacity duration-300">

            <!-- Modal Card Container -->
            <div class="bg-white rounded-3xl shadow-2xl max-w-lg w-full mx-4 p-8 transform scale-95 transition-transform duration-300" id="modalCard">

                <form id="checkoutProcessForm" action="{{ route('checkout.process') }}" method="POST">
                    @csrf

                    <!-- ================= STEP 1: ORDER SUMMARY ================= -->
                    <div id="modalStepSummary">
                        <div class="flex justify-between items-center border-b pb-4 mb-6">
                            <h3 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
                                <span>📋</span> Order Summary
                            </h3>
                            <button onclick="closeOrderSummaryModal()" type="button" class="text-gray-400 hover:text-gray-600 text-2xl font-bold focus:outline-none">
                                &times;
                            </button>
                        </div>

                        <div class="max-h-56 overflow-y-auto space-y-3 pr-2 mb-6 divide-y divide-gray-100">
                            @forelse($cart as $item)
                            @php
                            $subtotal = $item['price'] * $item['qty'];
                            @endphp
                            <div class="pt-3 flex justify-between items-center">
                                <div>
                                    <p class="font-bold text-gray-800 text-base">{{ $item['name'] }}</p>
                                    <p class="text-xs text-gray-500">₱{{ number_format($item['price'], 2) }} × {{ $item['qty'] }}</p>
                                </div>
                                <span class="font-bold text-gray-900">₱{{ number_format($subtotal, 2) }}</span>
                            </div>
                            @empty
                            <div class="text-center py-6 text-gray-500">
                                <p>Your cart is empty.</p>
                            </div>
                            @endforelse
                        </div>

                        <div class="bg-red-50 p-4 rounded-2xl flex justify-between items-center mb-6 border border-red-100">
                            <span class="text-gray-700 font-bold">Total Amount:</span>
                            <span class="text-2xl font-black text-red-600">₱{{ number_format($grandTotal, 2) }}</span>
                        </div>

                        <div class="flex gap-4">
                            <button onclick="closeOrderSummaryModal()" type="button"
                                class="w-1/2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-3 rounded-xl transition text-center">
                                Cancel
                            </button>

                            <button onclick="goToFormStep()" type="button"
                                class="w-1/2 bg-red-600 hover:bg-red-700 text-white font-bold py-3 rounded-xl transition text-center shadow-lg active:scale-95">
                                Continue →
                            </button>
                        </div>
                    </div>

                    <!-- ================= STEP 2: CUSTOMER DETAILS ================= -->
                    <div id="modalStepForm" class="hidden">
                        <div class="flex justify-between items-center border-b pb-4 mb-6">
                            <h3 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
                                <span>🛵</span> Delivery Details
                            </h3>
                            <button onclick="closeOrderSummaryModal()" type="button" class="text-gray-400 hover:text-gray-600 text-2xl font-bold focus:outline-none">
                                &times;
                            </button>
                        </div>

                        <div class="space-y-4 text-left mb-6">
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">Full Name</label>
                                <input type="text" name="custName" id="custNameInput" placeholder="e.g. Juan Dela Cruz"
                                    class="w-full border border-gray-300 rounded-xl p-3 text-sm focus:ring-2 focus:ring-red-500 focus:outline-none bg-gray-50">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">Contact Number</label>
                                <input type="text" name="custContact" id="custContactInput" placeholder="09123456789 or email@domain.com"
                                    class="w-full border border-gray-300 rounded-xl p-3 text-sm focus:ring-2 focus:ring-red-500 focus:outline-none bg-gray-50">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">Delivery Address</label>
                                <input type="text" name="custAddress" id="custAddressInput" placeholder="House No., Street, Barangay"
                                    class="w-full border border-gray-300 rounded-xl p-3 text-sm focus:ring-2 focus:ring-red-500 focus:outline-none bg-gray-50">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">
                                    Preferred Time <span class="text-gray-400 font-normal">(Optional)</span>
                                </label>
                                <input type="time" name="custTime"
                                    class="w-full border border-gray-300 rounded-xl p-3 text-sm focus:ring-2 focus:ring-red-500 focus:outline-none bg-gray-50">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">
                                    Special Notes / Instructions <span class="text-gray-400 font-normal">(Optional)</span>
                                </label>
                                <textarea name="custNotes" rows="2" placeholder="e.g. Extra sauce, no onions, or landmark near house..."
                                    class="w-full border border-gray-300 rounded-xl p-3 text-sm focus:ring-2 focus:ring-red-500 focus:outline-none bg-gray-50 resize-none"></textarea>
                            </div>
                        </div>

                        <div class="flex gap-4">
                            <button onclick="goToSummaryStep()" type="button"
                                class="w-1/3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-3 rounded-xl transition text-center">
                                ← Back
                            </button>

                            <button onclick="goToPaymentStep()" type="button"
                                class="w-2/3 bg-red-600 hover:bg-red-700 text-white font-bold py-3 rounded-xl transition text-center shadow-lg active:scale-95 flex items-center justify-center gap-1">
                                Proceed for Payment →
                            </button>
                        </div>
                    </div>

                    <!-- ================= STEP 3: PAYMENT METHOD ================= -->
                    <div id="modalStepPayment" class="hidden">
                        <div class="flex justify-between items-center border-b pb-4 mb-6">
                            <h3 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
                                <span>💳</span> Choose Payment
                            </h3>
                            <button onclick="closeOrderSummaryModal()" type="button" class="text-gray-400 hover:text-gray-600 text-2xl font-bold focus:outline-none">
                                &times;
                            </button>
                        </div>

                        <div class="space-y-4 text-left mb-6 max-h-[50vh] overflow-y-auto pr-1">
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">Payment Option</label>
                                <select name="custPayment" id="paymentMethodSelect" onchange="togglePaymentView()"
                                    class="w-full border border-gray-300 rounded-xl p-3 text-sm focus:ring-2 focus:ring-red-500 focus:outline-none bg-gray-50 font-bold text-gray-800">
                                    <option value="Cash on Delivery (COD)">Cash on Delivery (COD)</option>
                                    <option value="GCash">GCash</option>
                                </select>
                            </div>

                            <!-- GCASH QR SECTION -->
                            <div id="gcashQrSection" class="hidden bg-blue-50 border border-blue-200 rounded-2xl p-4 text-center space-y-3">
                                <div class="flex items-center justify-center gap-2 text-blue-700 font-bold text-sm">
                                    <span>📱</span> Scan QR Code via GCash
                                </div>

                                <div class="bg-white p-3 rounded-xl inline-block shadow-md">
                                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=GCash-EatshereDiner-Payment"
                                        alt="GCash Payment QR Code" class="w-36 h-36 mx-auto rounded-lg">
                                </div>

                                <p class="text-xs text-blue-900 font-medium">
                                    Please scan and pay <strong>₱{{ number_format($grandTotal, 2) }}</strong>, then input your 13-digit Reference No. below:
                                </p>

                                <input type="text" name="gcashRefNo" id="gcashRefNoInput" placeholder="GCash Ref No. (e.g. 1002938475839)"
                                    class="w-full border border-blue-300 rounded-xl p-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none bg-white text-center font-mono">
                            </div>
                        </div>

                        <div class="flex gap-4">
                            <button onclick="goToFormStep()" type="button"
                                class="w-1/3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-3 rounded-xl transition text-center">
                                ← Back
                            </button>

                            <button type="submit" id="submitOrderBtn"
                                class="w-2/3 bg-green-600 hover:bg-green-700 text-white font-bold py-3 rounded-xl transition text-center shadow-lg active:scale-95 flex items-center justify-center gap-2">
                                <span>Confirm & Pay</span> ✅
                            </button>
                        </div>
                    </div>

                    <!-- ================= STEP 4: SUCCESS CONFIRMATION BANNER ================= -->
                    <div id="modalStepSuccess" class="hidden text-center py-4">
                        <div class="w-20 h-20 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-4xl mx-auto mb-4 animate-bounce">
                            🎉
                        </div>

                        <h3 class="text-2xl font-black text-gray-900 mb-1">
                            Order Placed Successfully!
                        </h3>
                        <p class="text-gray-500 text-sm mb-6">
                            Thank you for ordering with Eatshere Diner.
                        </p>

                        <div class="bg-gray-50 border border-gray-200 rounded-2xl p-5 mb-6 text-left space-y-3">
                            <div class="flex justify-between items-center border-b pb-3">
                                <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Tracking Order No.</span>
                                <span class="font-mono font-black text-red-600 text-lg" id="displayOrderNumber">#ESH-000000</span>
                            </div>
                            <div class="text-xs text-gray-600 space-y-1">
                                <p><strong>Status:</strong> <span class="text-orange-600 font-bold">Preparing Meal 🍳</span></p>
                                <p>Please keep your order number to track your food delivery status.</p>
                            </div>
                        </div>

                        <button onclick="window.location.href='{{ route('test') }}'" type="button"
                            class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-3.5 rounded-xl shadow-lg transition active:scale-95">
                            Return to Main Menu 🍽️
                        </button>
                    </div>

                </form>

            </div>
        </div>

        <!-- JAVASCRIPT CONTROL & FIXED AJAX -->
        <script>
            function openOrderSummaryModal() {
                goToSummaryStep();

                const modal = document.getElementById('orderSummaryModal');
                const card = document.getElementById('modalCard');

                modal.classList.remove('hidden');
                setTimeout(() => {
                    modal.classList.remove('opacity-0');
                    card.classList.remove('scale-95');
                    card.classList.add('scale-100');
                }, 10);
            }

            function closeOrderSummaryModal() {
                const modal = document.getElementById('orderSummaryModal');
                const card = document.getElementById('modalCard');

                modal.classList.add('opacity-0');
                card.classList.remove('scale-100');
                card.classList.add('scale-95');

                setTimeout(() => {
                    modal.classList.add('hidden');
                }, 300);
            }

            function hideAllSteps() {
                document.getElementById('modalStepSummary').classList.add('hidden');
                document.getElementById('modalStepForm').classList.add('hidden');
                document.getElementById('modalStepPayment').classList.add('hidden');
                document.getElementById('modalStepSuccess').classList.add('hidden');
            }

            function goToSummaryStep() {
                hideAllSteps();
                document.getElementById('modalStepSummary').classList.remove('hidden');
            }

            function goToFormStep() {
                hideAllSteps();
                document.getElementById('modalStepForm').classList.remove('hidden');
            }

            function goToPaymentStep() {
                const name = document.getElementById('custNameInput').value.trim();
                const contact = document.getElementById('custContactInput').value.trim();
                const address = document.getElementById('custAddressInput').value.trim();

                if (!name || !contact || !address) {
                    alert('Please fill out all required fields: Full Name, Contact, and Address.');
                    return;
                }

                hideAllSteps();
                document.getElementById('modalStepPayment').classList.remove('hidden');
                togglePaymentView();
            }

            function togglePaymentView() {
                const paymentSelect = document.getElementById('paymentMethodSelect');
                const gcashSection = document.getElementById('gcashQrSection');

                if (paymentSelect && paymentSelect.value === 'GCash') {
                    gcashSection.classList.remove('hidden');
                } else if (gcashSection) {
                    gcashSection.classList.add('hidden');
                }
            }

            document.addEventListener('DOMContentLoaded', function() {
                const checkoutForm = document.getElementById('checkoutProcessForm');

                if (checkoutForm) {
                    checkoutForm.addEventListener('submit', function(e) {
                        e.preventDefault();

                        const paymentSelect = document.getElementById('paymentMethodSelect').value;
                        const gcashRef = document.getElementById('gcashRefNoInput').value.trim();

                        if (paymentSelect === 'GCash' && !gcashRef) {
                            alert('Please enter your GCash reference number before proceeding.');
                            return;
                        }

                        const submitBtn = document.getElementById('submitOrderBtn');
                        submitBtn.disabled = true;
                        submitBtn.innerText = 'Processing...';

                        const formData = new FormData(this);

                        // Safely retrieve CSRF token from input or meta tag
                        const csrfToken = document.querySelector('input[name="_token"]')?.value ||
                            document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

                        fetch(this.action, {
                                method: 'POST',
                                body: formData,
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'X-CSRF-TOKEN': csrfToken
                                }
                            })
                            .then(async response => {
                                const data = await response.json();
                                if (!response.ok) {
                                    throw new Error(data.message || 'Something went wrong while processing your order.');
                                }
                                return data;
                            })
                            .then(data => {
                                if (data.success) {
                                    document.getElementById('displayOrderNumber').innerText = data.order_number;

                                    hideAllSteps();
                                    document.getElementById('modalStepSuccess').classList.remove('hidden');
                                } else {
                                    alert(data.message || 'There was an issue processing your order.');
                                    submitBtn.disabled = false;
                                    submitBtn.innerText = 'Confirm & Pay ✅';
                                }
                            })
                            .catch(error => {
                                console.error('Checkout error:', error);
                                alert(error.message);
                                submitBtn.disabled = false;
                                submitBtn.innerText = 'Confirm & Pay ✅';
                            });
                    });
                }
            });
        </script>

    </div>

    @else

    <div class="bg-white rounded-2xl shadow-lg p-16 text-center">
        <h2 class="text-3xl font-bold text-gray-600">
            Your cart is empty.
        </h2>

        <p class="mt-3 text-gray-500">
            Add some delicious meals first.
        </p>

        <a href="{{ route('test') }}"
            class="inline-block mt-8 bg-red-600 hover:bg-red-700 text-white px-8 py-3 rounded-xl font-bold transition">
            Browse Menu
        </a>
    </div>

    @endif

</div>

@include('layouts.footer')