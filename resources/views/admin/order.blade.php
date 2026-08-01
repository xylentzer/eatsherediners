@include('layouts.side-dashboard')

<style>
  @keyframes fadeInUp {
    0% {
      opacity: 0;
      transform: translateY(16px)
    }

    100% {
      opacity: 1;
      transform: translateY(0)
    }
  }

  .animate-fadeInUp {
    animation: fadeInUp .45s ease-out forwards
  }

  .status-pill {
    padding: .25rem .6rem;
    border-radius: 999px;
    font-weight: 600;
    font-size: .8rem
  }

  @media print {
    body * {
      visibility: hidden;
    }

    #printArea,
    #printArea * {
      visibility: visible;
    }

    #printArea {
      position: absolute;
      left: 0;
      top: 0;
      width: 100%;
    }
  }
</style>

<section class="bg-gray-50 min-h-screen py-8 px-4">
  <div class="max-w-7xl mx-auto">

    <header class="flex items-center justify-between mb-8">
      <div>
        <h1 class="text-3xl font-extrabold text-red-600">📥 Incoming Orders</h1>
        <p class="text-sm text-gray-600">Manage orders: view details, update status, print receipts.</p>
      </div>
    </header>

    @php
    $statuses = ['Pending', 'Accepted', 'In Progress', 'To Deliver', 'Completed', 'Aborted'];
    @endphp

    <!-- DYNAMIC STATUS COLUMNS -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      @foreach($statuses as $status)
      @php
      $filteredOrders = $orders->where('status', $status);

      $colorClass = match($status) {
      'Pending' => 'text-red-600',
      'Accepted' => 'text-green-600',
      'In Progress' => 'text-yellow-600',
      'To Deliver' => 'text-indigo-600',
      'Completed' => 'text-gray-700',
      'Aborted' => 'text-red-700',
      default => 'text-gray-700'
      };

      $pillClass = match($status) {
      'Pending' => 'bg-red-100 text-red-700',
      'Accepted' => 'bg-green-100 text-green-700',
      'In Progress' => 'bg-yellow-100 text-yellow-700',
      'To Deliver' => 'bg-indigo-100 text-indigo-700',
      'Completed' => 'bg-gray-100 text-gray-700',
      'Aborted' => 'bg-red-100 text-red-700',
      default => 'bg-gray-100 text-gray-700'
      };
      @endphp

      <div class="bg-white rounded-2xl shadow-md p-5 border border-gray-100 flex flex-col justify-between">
        <div>
          <!-- Column Header -->
          <div class="flex items-center justify-between mb-4 border-b pb-3">
            <div>
              <h3 class="text-lg font-bold {{ $colorClass }}">{{ $status }}</h3>
              <div class="text-xs text-gray-500 font-medium">{{ $filteredOrders->count() }} order(s)</div>
            </div>
            <span class="status-pill {{ $pillClass }}">{{ $status }}</span>
          </div>

          <!-- Orders List -->
          <div class="space-y-3">
            @forelse($filteredOrders as $order)
            <div onclick="openOrderModal({{ json_encode($order) }})"
              class="border border-gray-200 rounded-xl p-4 bg-gray-50 hover:bg-white hover:shadow-md transition cursor-pointer active:scale-98">
              <div class="flex items-start justify-between gap-2">
                <div>
                  <div class="font-bold text-gray-900 text-base">{{ $order->customer_name }}</div>
                  <div class="text-xs text-gray-500 mt-1">Delivery • {{ $order->delivery_address }}</div>
                  <div class="text-xs font-mono text-gray-400 mt-2 font-semibold">Order # {{ $order->order_number }}</div>
                </div>
                <div class="text-right">
                  <div class="font-black text-gray-900 text-lg">₱{{ number_format($order->total_amount, 2) }}</div>
                  <div class="text-[10px] text-gray-400 mt-1">{{ $order->created_at->format('m/d/Y, h:i A') }}</div>
                </div>
              </div>

              <!-- Order Items Summary -->
              <div class="mt-3 pt-3 border-t text-xs text-gray-600 space-y-1">
                @foreach($order->items as $item)
                <p class="truncate">• {{ $item->quantity }}x {{ $item->item_name }} (₱{{ number_format($item->subtotal, 2) }})</p>
                @endforeach
              </div>
            </div>
            @empty
            <div class="text-center py-8 text-gray-400 text-sm italic">
              No {{ strtolower($status) }} orders right now.
            </div>
            @endforelse
          </div>
        </div>
      </div>
      @endforeach
    </div>

  </div>
</section>

<!-- ORDER DETAILS & RECEIPT MODAL -->
<div id="orderModal" class="hidden fixed inset-0 bg-black/60 z-50 flex items-center justify-center p-4 backdrop-blur-sm">
  <div class="bg-white rounded-3xl shadow-2xl w-full max-w-4xl p-6 relative overflow-auto" style="max-height:90vh;">
    <button id="closeOrderModal" class="absolute top-4 right-4 text-gray-400 hover:text-gray-800 text-3xl font-bold focus:outline-none">&times;</button>

    <div class="flex items-start justify-between mb-6 border-b pb-4">
      <div>
        <h2 id="modalOrderNo" class="text-2xl font-black text-red-600">#ESH-000000</h2>
        <p id="modalPlacedAt" class="text-xs text-gray-500 mt-1">Placed: —</p>
      </div>

      <div class="flex gap-2 items-center pr-8">
        <select id="modalStatusSelect" class="border border-gray-300 rounded-xl px-3 py-2 text-sm font-semibold text-gray-800 focus:ring-2 focus:ring-red-500">
          <option value="Pending">Pending</option>
          <option value="Accepted">Accepted</option>
          <option value="In Progress">In Progress</option>
          <option value="To Deliver">To Deliver</option>
          <option value="Completed">Completed</option>
          <option value="Aborted">Aborted</option>
        </select>

        <button id="saveStatusBtn" class="bg-red-600 hover:bg-red-700 text-white font-bold px-4 py-2 rounded-xl text-sm shadow transition">Update</button>
      </div>
    </div>

    <div class="grid md:grid-cols-2 gap-6">
      <!-- Left: Customer Details & Order Items -->
      <div class="space-y-4">
        <div class="bg-gray-50 rounded-2xl p-4 border border-gray-200">
          <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider mb-2">Customer & Delivery Details</h3>
          <div id="modalCustomer" class="text-sm text-gray-700 space-y-1"></div>
        </div>

        <div class="bg-gray-50 rounded-2xl p-4 border border-gray-200">
          <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider mb-2">Ordered Items</h3>
          <div id="modalItems" class="space-y-2 text-sm text-gray-700"></div>
        </div>

        <div class="flex flex-wrap gap-2 pt-2">
          <button id="btnPrint" class="bg-gray-800 hover:bg-black text-white font-bold px-4 py-2.5 rounded-xl text-xs transition shadow">🖨️ Print Receipt</button>
          <button onclick="quickUpdateStatus('Accepted')" class="bg-green-600 hover:bg-green-700 text-white font-bold px-3 py-2 rounded-xl text-xs">Accept</button>
          <button onclick="quickUpdateStatus('In Progress')" class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold px-3 py-2 rounded-xl text-xs">Start</button>
          <button onclick="quickUpdateStatus('To Deliver')" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold px-3 py-2 rounded-xl text-xs">Deliver</button>
          <button onclick="quickUpdateStatus('Completed')" class="bg-gray-700 hover:bg-gray-900 text-white font-bold px-3 py-2 rounded-xl text-xs">Complete</button>
        </div>
      </div>

      <!-- Right: Printable Receipt Preview -->
      <div>
        <div id="printArea" class="bg-white border border-gray-200 rounded-2xl p-5 text-sm shadow-inner">
          <div class="text-center mb-4">
            <h3 class="text-2xl font-black text-red-600">EatsHere Diner</h3>
            <div class="text-xs text-gray-400 font-medium">Official Receipt / Customer Copy</div>
          </div>

          <div class="mb-3 text-xs text-gray-600 space-y-1 border-b pb-3" id="receiptMeta"></div>

          <div class="py-2 space-y-1" id="receiptItems"></div>

          <div class="border-t mt-3 pt-3 text-sm space-y-1">
            <div class="flex justify-between text-gray-600"><span>Payment Option:</span><span id="receiptPaymentMethod" class="font-bold text-gray-800">COD</span></div>
            <div class="flex justify-between font-black text-lg mt-2 pt-2 border-t text-gray-900"><span>Grand Total</span><span id="receiptTotal" class="text-red-600">₱0.00</span></div>
          </div>

          <p class="text-center text-xs text-gray-400 mt-6">Thank you for dining with EatsHere Diner! 🍽️</p>
        </div>
      </div>
    </div>

  </div>
</div>

<script>
let currentActiveOrder = null;

function openOrderModal(order) {
  currentActiveOrder = order;

  // Header Details
  document.getElementById('modalOrderNo').innerText = 'Order ' + order.order_number;
  document.getElementById('modalPlacedAt').innerText = 'Placed: ' + new Date(order.created_at).toLocaleString();
  document.getElementById('modalStatusSelect').value = order.status;

  // Customer Section
  document.getElementById('modalCustomer').innerHTML = `
    <div><strong>Name:</strong> ${escapeHtml(order.customer_name)}</div>
    <div><strong>Contact:</strong> ${escapeHtml(order.customer_contact)}</div>
    <div><strong>Address:</strong> ${escapeHtml(order.delivery_address)}</div>
    ${order.preferred_time ? `<div><strong>Time:</strong> ${escapeHtml(order.preferred_time)}</div>` : ''}
    ${order.gcash_ref_no ? `<div><strong>GCash Ref:</strong> <span class="font-mono text-blue-600 font-bold">${escapeHtml(order.gcash_ref_no)}</span></div>` : ''}
    ${order.special_notes ? `<div><strong>Notes:</strong> ${escapeHtml(order.special_notes)}</div>` : ''}
  `;

  // Items Section
  const itemsContainer = document.getElementById('modalItems');
  itemsContainer.innerHTML = '';
  
  order.items.forEach(it => {
    const row = document.createElement('div');
    row.className = 'flex justify-between items-center border-b pb-1';
    row.innerHTML = `
      <div>${escapeHtml(it.item_name)} <span class="text-xs text-gray-400 font-bold">x${it.quantity}</span></div>
      <div class="font-bold">₱${numberFormat(it.subtotal)}</div>
    `;
    itemsContainer.appendChild(row);
  });

  // Receipt Meta Section
  document.getElementById('receiptMeta').innerHTML = `
    <div><strong>Order #:</strong> ${escapeHtml(order.order_number)}</div>
    <div><strong>Date:</strong> ${new Date(order.created_at).toLocaleString()}</div>
    <div><strong>Customer:</strong> ${escapeHtml(order.customer_name)}</div>
    <div><strong>Address:</strong> ${escapeHtml(order.delivery_address)}</div>
  `;

  // Receipt Items
  const receiptItemsContainer = document.getElementById('receiptItems');
  receiptItemsContainer.innerHTML = '';
  order.items.forEach(it => {
    const r = document.createElement('div');
    r.className = 'flex justify-between text-xs text-gray-700';
    r.innerHTML = `<div>${escapeHtml(it.item_name)} x${it.quantity}</div><div>₱${numberFormat(it.subtotal)}</div>`;
    receiptItemsContainer.appendChild(r);
  });

  document.getElementById('receiptPaymentMethod').innerText = order.payment_method;
  document.getElementById('receiptTotal').innerText = '₱' + numberFormat(order.total_amount);

  // Show Modal
  document.getElementById('orderModal').classList.remove('hidden');
}

function quickUpdateStatus(newStatus) {
  if (currentActiveOrder) {
    document.getElementById('modalStatusSelect').value = newStatus;
    saveStatusUpdate(newStatus);
  }
}

document.getElementById('saveStatusBtn').addEventListener('click', () => {
  const selectedStatus = document.getElementById('modalStatusSelect').value;
  saveStatusUpdate(selectedStatus);
});

// Sends PATCH AJAX request to database
function saveStatusUpdate(status) {
  if (!currentActiveOrder) return;

  const btn = document.getElementById('saveStatusBtn');
  btn.disabled = true;
  btn.innerText = 'Saving...';

  const updateUrl = `/admin/order/${currentActiveOrder.id}/status`;

  fetch(updateUrl, {
      method: 'PATCH',
      headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': '{{ csrf_token() }}',
          'X-Requested-With': 'XMLHttpRequest'
      },
      body: JSON.stringify({ status: status })
  })
  .then(res => res.json())
  .then(data => {
      if (data.success) {
          window.location.reload(); // Reloads to reflect new column position
      } else {
          alert(data.message || 'Error updating status.');
          btn.disabled = false;
          btn.innerText = 'Update';
      }
  })
  .catch(err => {
      console.error('Update Error:', err);
      alert('Failed to update status.');
      btn.disabled = false;
      btn.innerText = 'Update';
  });
}

// Helpers
function numberFormat(n){ return parseFloat(n||0).toLocaleString(undefined,{minimumFractionDigits:2, maximumFractionDigits:2}); }
function escapeHtml(s){ return String(s||'').replace(/[&<>"'`=\/]/g, ch => ({ "&":"&amp;","<":"&lt;",">":"&gt;",'"':"&quot;","'":"&#39;","/":"&#x2F;","`":"&#x60;","=":"&#x3D;" }[ch])); }

// Close Modal Event Listeners
document.getElementById('closeOrderModal').addEventListener('click', () => { 
  document.getElementById('orderModal').classList.add('hidden'); 
});

document.getElementById('btnPrint').addEventListener('click', () => { 
  window.print(); 
});
</script>