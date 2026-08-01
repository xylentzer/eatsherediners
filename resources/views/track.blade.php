@include('layouts.header')

@vite(['resources/css/app.css','resources/js/app.js'])

<div class="max-w-4xl mx-auto px-6 py-12">

    <h1 class="text-3xl font-extrabold text-red-600 mb-8 text-center">
        📍 Track Your Order
    </h1>

    <!-- Search Form -->
    <form action="{{ route('track.search') }}" method="POST" class="flex gap-3 mb-10 max-w-lg mx-auto">
        @csrf
        <input type="text" name="order_number" placeholder="Enter Order No. (e.g. ESH-2B9548)"
            value="{{ old('order_number', $order->order_number ?? '') }}" required
            class="flex-1 border border-gray-300 rounded-xl p-3 text-sm focus:ring-2 focus:ring-red-500 focus:outline-none shadow-sm">
        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold px-6 py-3 rounded-xl shadow transition">
            Track
        </button>
    </form>

    @if(session('error'))
    <div class="max-w-lg mx-auto bg-red-100 border border-red-300 text-red-700 p-4 rounded-xl text-center mb-6 font-semibold text-sm">
        {{ session('error') }}
    </div>
    @endif

    @if(session('success'))
    <div class="max-w-lg mx-auto bg-green-100 border border-green-300 text-green-700 p-4 rounded-xl text-center mb-6 font-semibold text-sm">
        {{ session('success') }}
    </div>
    @endif

    @if(isset($order))
    @php
    $adminStatuses = ['Pending', 'Accepted', 'In Progress', 'To Deliver', 'Completed'];

    $currentIndex = array_search($order->status, $adminStatuses);
    if ($currentIndex === false) {
    $currentIndex = ($order->status === 'Aborted') ? -1 : 0;
    }

    // Check if cancellation/modification can be requested directly
    $canCancel = in_array($order->status, ['Pending', 'Accepted']);
    @endphp

    <div class="bg-white rounded-3xl shadow-xl p-8 border border-gray-100">

        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start gap-4 border-b pb-6 mb-8">
            <div>
                <h2 class="text-2xl font-black text-gray-900">
                    Order {{ str_starts_with($order->order_number, '#') ? $order->order_number : '#' . $order->order_number }}
                </h2>
                <p class="text-gray-500 font-semibold text-sm mt-1">{{ $order->customer_name }}</p>
                <p class="text-xs text-gray-400 mt-1">Placed: {{ $order->created_at->format('M d, Y • h:i A') }}</p>
            </div>

            <span class="px-4 py-1.5 rounded-full text-sm font-bold shadow-sm 
                {{ $order->status === 'Aborted' ? 'bg-red-100 text-red-600' : 'bg-red-50 text-red-600 border border-red-200' }}">
                {{ $order->status }}
            </span>
        </div>

        @if($order->status === 'Aborted')
        <!-- ABORTED BANNER -->
        <div class="bg-red-50 border border-red-200 rounded-2xl p-6 text-center text-red-600 font-bold mb-8">
            ⚠️ This order has been cancelled/aborted. Please contact support for details.
        </div>
        @else
        <!-- DYNAMIC ADMIN STATUS TRACKER -->
        <div class="space-y-6 mb-10">
            @foreach($adminStatuses as $index => $statusName)
            @php
            $isCompleted = $currentIndex >= $index;
            $isCurrent = $currentIndex === $index;
            @endphp
            <div class="flex items-center gap-4">
                <!-- Circle Indicator -->
                <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-sm transition-all duration-300
                            {{ $isCompleted ? 'bg-green-500 text-white shadow-md' : 'bg-gray-200 text-gray-400' }}">
                    @if($isCompleted)
                    ✓
                    @else
                    {{ $index + 1 }}
                    @endif
                </div>

                <!-- Status Title -->
                <div class="flex-1">
                    <p class="font-bold text-sm {{ $isCompleted ? 'text-gray-900' : 'text-gray-400' }}">
                        {{ $statusName }}
                    </p>
                    @if($isCurrent)
                    <p class="text-xs text-green-600 font-semibold animate-pulse">Current Status</p>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        @endif

        <!-- Order Items Summary -->
        <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200 mb-8">
            <h3 class="font-bold text-gray-800 text-sm uppercase tracking-wider mb-4">Ordered Items</h3>
            <div class="space-y-3 text-sm text-gray-700 divide-y divide-gray-200">
                @foreach($order->items as $item)
                <div class="pt-2 flex justify-between items-center">
                    <div>
                        <span class="font-bold text-gray-900">{{ $item->quantity }}x</span> {{ $item->item_name }}
                    </div>
                    <span class="font-bold text-gray-900">₱{{ number_format($item->subtotal, 2) }}</span>
                </div>
                @endforeach
            </div>

            <div class="border-t border-gray-300 mt-4 pt-4 flex justify-between items-center font-black text-lg text-gray-900">
                <span>Total Amount</span>
                <span class="text-red-600">₱{{ number_format($order->total_amount, 2) }}</span>
            </div>
        </div>

        <!-- ACTION BUTTONS: MODIFICATION & CANCELLATION -->
        @if($order->status !== 'Aborted' && $order->status !== 'Completed')
        <div class="flex flex-col sm:flex-row gap-4 justify-end pt-2 border-t">

            <!-- Request Modification Button -->
            <button type="button" onclick="openModifyModal()"
                class="bg-gray-800 hover:bg-black text-white font-bold py-3 px-6 rounded-xl text-sm transition shadow-md active:scale-95">
                ✏️ Request Modification
            </button>

            <!-- Request Cancellation Button -->
            <button type="button" onclick="openCancelModal()"
                class="bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-6 rounded-xl text-sm transition shadow-md active:scale-95">
                🚫 Cancel Order
            </button>
        </div>
        @endif

    </div>
    @endif

</div>

<!-- ================= MODAL: REQUEST MODIFICATION ================= -->
<div id="modifyModal" class="fixed inset-0 bg-black/60 z-50 flex items-center justify-center p-4 hidden backdrop-blur-sm">
    <div class="bg-white rounded-3xl p-6 max-w-md w-full shadow-2xl relative">
        <button type="button" onclick="closeModifyModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 font-bold text-2xl">&times;</button>

        <h3 class="text-2xl font-bold text-gray-900 mb-2">Request Modification</h3>
        <p class="text-xs text-gray-500 mb-6">Select what you would like to modify and specify your instructions.</p>

        <form id="modifyForm" action="#" method="POST">
            @csrf
            <input type="hidden" name="order_id" value="{{ $order->id ?? '' }}">

            <div class="space-y-4 mb-6">
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-2">Type of Modification</label>
                    <select name="modification_type" required class="w-full border border-gray-300 rounded-xl p-3 text-sm focus:ring-2 focus:ring-red-500 focus:outline-none bg-gray-50 font-bold text-gray-800">
                        <option value="Delivery Profile">Delivery Profile (Address / Contact / Time)</option>
                        <option value="Order Items">Order Items / Special Instructions</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-2">Note / Changes Details</label>
                    <textarea name="modification_note" rows="3" required placeholder="Please describe the changes you need..."
                        class="w-full border border-gray-300 rounded-xl p-3 text-sm focus:ring-2 focus:ring-red-500 focus:outline-none bg-gray-50 resize-none"></textarea>
                </div>
            </div>

            <div class="flex gap-3">
                <button type="button" onclick="closeModifyModal()" class="w-1/3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-3 rounded-xl text-sm">Cancel</button>
                <button type="button" onclick="submitModificationRequest()" class="w-2/3 bg-gray-900 hover:bg-black text-white font-bold py-3 rounded-xl text-sm shadow">Submit Request</button>
            </div>
        </form>
    </div>
</div>

<!-- ================= MODAL: REQUEST CANCELLATION ================= -->
<div id="cancelModal" class="fixed inset-0 bg-black/60 z-50 flex items-center justify-center p-4 hidden backdrop-blur-sm">
    <div class="bg-white rounded-3xl p-6 max-w-md w-full shadow-2xl relative">
        <button type="button" onclick="closeCancelModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 font-bold text-2xl">&times;</button>

        <h3 class="text-2xl font-bold text-red-600 mb-2">Cancel Order</h3>

        {{-- Safe null-coalescing check so it never throws Undefined Variable --}}
        @if($canCancel ?? false)
            <p class="text-xs text-gray-500 mb-6">Please provide a reason for cancelling your order.</p>

            <form id="cancelForm" action="#" method="POST">
                @csrf
                <input type="hidden" name="order_id" value="{{ $order->id ?? '' }}">

                <div class="mb-6">
                    <label class="block text-xs font-bold text-gray-700 mb-2">Reason for Cancellation</label>
                    <textarea name="cancellation_reason" rows="3" required placeholder="e.g. Changed my mind, ordered by mistake, duplicate order..." 
                              class="w-full border border-gray-300 rounded-xl p-3 text-sm focus:ring-2 focus:ring-red-500 focus:outline-none bg-gray-50 resize-none"></textarea>
                </div>

                <div class="flex gap-3">
                    <button type="button" onclick="closeCancelModal()" class="w-1/3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-3 rounded-xl text-sm">Back</button>
                    <button type="button" onclick="submitCancellationRequest()" class="w-2/3 bg-red-600 hover:bg-red-700 text-white font-bold py-3 rounded-xl text-sm shadow">Confirm Cancellation</button>
                </div>
            </form>
        @else
            <!-- LOCKED STATE: ORDER IS IN PROGRESS OR BEYOND -->
            <div class="bg-amber-50 border border-amber-200 text-amber-800 rounded-2xl p-4 text-xs space-y-2 mb-6 mt-2">
                <p class="font-bold text-sm text-amber-900">⚠️ Cancellation Unavailable</p>
                <p>Your order is currently in <strong>{{ $order->status ?? 'Processing' }}</strong> status. Kitchen preparation or delivery has already started, so automated cancellation is locked.</p>
                <p class="font-semibold text-gray-700">Please contact the admin directly to request assistance:</p>
                <p class="font-mono text-red-600 font-bold">📞 Hot Line: (02) 8123-4567 / 0917-123-4567</p>
            </div>

            <button type="button" onclick="closeCancelModal()" class="w-full bg-gray-900 text-white font-bold py-3 rounded-xl text-sm">Understand & Close</button>
        @endif
    </div>
</div>

<script>
    function openModifyModal() {
        document.getElementById('modifyModal').classList.remove('hidden');
    }

    function closeModifyModal() {
        document.getElementById('modifyModal').classList.add('hidden');
    }

    function openCancelModal() {
        document.getElementById('cancelModal').classList.remove('hidden');
    }

    function closeCancelModal() {
        document.getElementById('cancelModal').classList.add('hidden');
    }

    function submitModificationRequest() {
        alert('Modification request submitted! Admin will review your changes shortly.');
        closeModifyModal();
    }

    function submitCancellationRequest() {
        alert('Cancellation request submitted successfully.');
        closeCancelModal();
    }
</script>

@include('layouts.footer')