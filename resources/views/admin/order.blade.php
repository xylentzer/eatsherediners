@include('layouts.side-dashboard')

<style>
  @keyframes fadeInUp { 0%{opacity:0;transform:translateY(16px)}100%{opacity:1;transform:translateY(0)}}
  .animate-fadeInUp{animation:fadeInUp .45s ease-out forwards}
  .status-pill{padding:.25rem .6rem;border-radius:999px;font-weight:600;font-size:.8rem}
  @media print {
    body * { visibility: hidden; }
    #printArea, #printArea * { visibility: visible; }
    #printArea { position: absolute; left: 0; top: 0; width: 100%; }
  }
</style>

<section class="bg-gray-50 min-h-screen py-8 px-4">
  <div class="max-w-6xl mx-auto">

    <header class="flex items-center justify-between mb-6">
      <div>
        <h1 class="text-3xl font-extrabold text-red-600">📥 Incoming Orders</h1>
        <p class="text-sm text-gray-600">Manage orders: view details, update status, print receipts.</p>
      </div>
      <div class="flex gap-2 items-center">
        <button id="btnNewOrder" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-full shadow">+ New Dummy Order</button>
        <button id="btnResetOrders" class="bg-gray-100 hover:bg-gray-200 text-gray-800 px-3 py-2 rounded-full">Reset Demo</button>
      </div>
    </header>

    <div id="ordersContainer" class="grid gap-6">
      <!-- JS renders grouped status columns here -->
    </div>

  </div>
</section>

<!-- Order Details Modal -->
<div id="orderModal" class="hidden fixed inset-0 bg-black/60 z-50 flex items-center justify-center p-4">
  <div class="bg-white rounded-2xl shadow-xl w-full max-w-4xl p-6 relative overflow-auto" style="max-height:90vh;">
    <button id="closeOrderModal" class="absolute top-4 right-4 text-gray-500 hover:text-gray-800 text-2xl">&times;</button>

    <div class="flex items-start justify-between mb-4">
      <div>
        <h2 id="modalOrderNo" class="text-xl font-bold text-red-600">Order #000000</h2>
        <p id="modalPlacedAt" class="text-sm text-gray-500 mt-1">Placed: —</p>
      </div>

      <div class="flex gap-3 items-center">
        <select id="modalStatus" class="border rounded-lg p-2">
          <option value="Pending">Pending</option>
          <option value="Accepted">Accepted</option>
          <option value="In Progress">In Progress</option>
          <option value="To Deliver">To Deliver</option>
          <option value="Completed">Completed</option>
          <option value="Aborted">Aborted</option>
        </select>

        <button id="saveStatusBtn" class="bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded-full">Update</button>
      </div>
    </div>

    <div class="grid md:grid-cols-2 gap-6">
      <!-- Left: Order & Customer details + admin actions -->
      <div class="space-y-4">
        <div class="bg-gray-50 rounded-xl p-4 border">
          <h3 class="text-lg font-semibold text-gray-800 mb-2">Customer & Delivery</h3>
          <div id="modalCustomer" class="text-sm text-gray-700 space-y-1">
            <!-- Name, email/phone, delivery address will render here -->
          </div>
        </div>

        <div class="bg-gray-50 rounded-xl p-4 border">
          <h3 class="text-lg font-semibold text-gray-800 mb-2">Order Items</h3>
          <div id="modalItems" class="space-y-2 text-sm text-gray-700">
            <!-- items list -->
          </div>
        </div>

        <div class="flex gap-2">
          <button id="btnPrint" class="bg-gray-200 hover:bg-gray-300 px-4 py-2 rounded-full">🖨️ Print Receipt</button>
          <button id="btnQuickAccepted" class="bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded-full">Accept</button>
          <button id="btnQuickInProgress" class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-2 rounded-full">Start</button>
          <button id="btnQuickDeliver" class="bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-2 rounded-full">To Deliver</button>
          <button id="btnQuickComplete" class="bg-gray-800 hover:bg-black text-white px-3 py-2 rounded-full">Complete</button>
        </div>
      </div>

      <!-- Right: Receipt preview (printable area) -->
      <div>
        <div id="printArea" class="bg-white border rounded-xl p-4 text-sm">
          <div class="text-center mb-4">
            <h3 class="text-2xl font-bold text-red-600">EatsHere Diner</h3>
            <div class="text-xs text-gray-500">Receipt / Customer Copy</div>
          </div>

          <div class="mb-3 text-xs text-gray-600" id="receiptMeta">
            <!-- Order meta -->
          </div>

          <div class="border-t pt-2" id="receiptItems">
            <!-- receipt items -->
          </div>

          <div class="border-t mt-3 pt-3 text-sm">
            <div class="flex justify-between"><span>Subtotal</span><span id="receiptSubtotal">₱0.00</span></div>
            <div class="flex justify-between"><span>Delivery Fee</span><span id="receiptDelivery">₱0.00</span></div>
            <div class="flex justify-between font-bold text-lg mt-2"><span>Total</span><span id="receiptTotal">₱0.00</span></div>
          </div>

          <p class="text-xs text-gray-500 mt-4">Thank you for ordering at EatsHere Diner!</p>
        </div>
      </div>
    </div>

  </div>
</div>

<script>
/* Incoming Orders Manager (frontend-only)
   - grouped by status
   - modal with details + receipt
   - status update & quick action buttons
   - print receipt
   - persists to localStorage
*/

const ORDERS_KEY = 'eatshed_incoming_orders_v1';

// statuses in desired order
const STATUSES = ['Pending','Accepted','In Progress','To Deliver','Completed','Aborted'];

// sample dummy orders
const DEFAULT_ORDERS = [
  {
    orderNo: 'ORD-20251104-1001',
    placedAt: '2025-11-04T09:12:00',
    customer: { name: 'Juan Dela Cruz', phone: '09171234567', email: 'juan@example.com' },
    type: 'Delivery',
    destination: 'Blk 3 Lot 5, BF Homes, Parañaque', // or "Pickup - Front Desk"
    items: [
      { name: 'Chicken Pastil (w/ Egg & Rice)', qty: 2, price: 70 },
      { name: '500ml Water', qty: 2, price: 14 }
    ],
    deliveryFee: 30,
    status: 'Pending',
    note: 'Gate code 1234'
  },

  {
    orderNo: 'ORD-20251104-1002',
    placedAt: '2025-11-04T09:30:00',
    customer: { name: 'Maria Clara', phone: '09179876543', email: 'maria@example.com' },
    type: 'Pickup',
    destination: 'Pickup - Front Desk',
    items: [
      { name: 'Pork Tonkatsu', qty: 1, price: 180 },
      { name: 'Extra Rice', qty: 1, price: 14 }
    ],
    deliveryFee: 0,
    status: 'Accepted',
    note: ''
  },

  {
    orderNo: 'ORD-20251104-1003',
    placedAt: '2025-11-04T10:00:00',
    customer: { name: 'Carlos Santos', phone: '09170001111', email: 'carlos@example.com' },
    type: 'Delivery',
    destination: 'Unit 12A, East Tower, 123 City Rd',
    items: [
      { name: 'Fried Chicken', qty: 3, price: 150 },
      { name: 'Half Rice', qty: 3, price: 7 }
    ],
    deliveryFee: 45,
    status: 'In Progress',
    note: 'No onions'
  }
];

// load/save orders
function loadOrders() {
  const raw = localStorage.getItem(ORDERS_KEY);
  if (!raw) {
    localStorage.setItem(ORDERS_KEY, JSON.stringify(DEFAULT_ORDERS));
    return JSON.parse(JSON.stringify(DEFAULT_ORDERS));
  }
  try {
    return JSON.parse(raw);
  } catch (e) {
    return JSON.parse(JSON.stringify(DEFAULT_ORDERS));
  }
}
function saveOrders(list) {
  localStorage.setItem(ORDERS_KEY, JSON.stringify(list));
}

let orders = loadOrders();

/* Render grouped columns */
const ordersContainer = document.getElementById('ordersContainer');

function renderOrders() {
  ordersContainer.innerHTML = '';

  // create a horizontal layout: show status header with count and column of cards
  const wrapper = document.createElement('div');
  wrapper.className = 'grid grid-cols-1 md:grid-cols-3 gap-6';

  STATUSES.forEach(status => {
    const col = document.createElement('div');
    col.className = 'bg-white rounded-2xl shadow p-4';

    const statusCount = orders.filter(o => o.status === status).length;
    col.innerHTML = `
      <div class="flex items-center justify-between mb-3">
        <div>
          <h3 class="text-lg font-bold ${statusColorClass(status)}">${status}</h3>
          <div class="text-xs text-gray-500">${statusCount} order(s)</div>
        </div>
        <div>
          <span class="status-pill ${statusColorBackground(status)}">${status}</span>
        </div>
      </div>
    `;

    const list = document.createElement('div');
    list.className = 'space-y-3';

    orders.filter(o => o.status === status).forEach(o => {
      const placed = new Date(o.placedAt);
      const card = document.createElement('div');
      card.className = 'border rounded-lg p-3 hover:shadow-md transition cursor-pointer';
      card.innerHTML = `
        <div class="flex items-start justify-between gap-2">
          <div>
            <div class="font-semibold text-gray-800">${escapeHtml(o.customer.name)}</div>
            <div class="text-xs text-gray-500">${escapeHtml(o.type)} • ${escapeHtml(o.destination)}</div>
            <div class="text-xs text-gray-500 mt-1">Order # ${escapeHtml(o.orderNo)}</div>
          </div>
          <div class="text-right">
            <div class="font-bold text-gray-900">₱${numberFormat(calculateTotal(o))}</div>
            <div class="text-xs text-gray-500">${placed.toLocaleString()}</div>
          </div>
        </div>
      `;
      card.addEventListener('click', () => openOrderModal(o.orderNo));
      list.appendChild(card);
    });

    col.appendChild(list);
    wrapper.appendChild(col);
  });

  ordersContainer.appendChild(wrapper);
}

/* modal & details rendering */
const orderModal = document.getElementById('orderModal');
const modalOrderNo = document.getElementById('modalOrderNo');
const modalPlacedAt = document.getElementById('modalPlacedAt');
const modalCustomer = document.getElementById('modalCustomer');
const modalItems = document.getElementById('modalItems');
const modalStatus = document.getElementById('modalStatus');
const receiptMeta = document.getElementById('receiptMeta');
const receiptItems = document.getElementById('receiptItems');
const receiptSubtotal = document.getElementById('receiptSubtotal');
const receiptDelivery = document.getElementById('receiptDelivery');
const receiptTotal = document.getElementById('receiptTotal');

function openOrderModal(orderNo) {
  const order = orders.find(o => o.orderNo === orderNo);
  if (!order) return alert('Order not found');
  // header
  modalOrderNo.innerText = order.orderNo;
  modalPlacedAt.innerText = `Placed: ${new Date(order.placedAt).toLocaleString()}`;
  // customer
  modalCustomer.innerHTML = `
    <div><strong>Name:</strong> ${escapeHtml(order.customer.name)}</div>
    <div><strong>Phone:</strong> ${escapeHtml(order.customer.phone || '-')}</div>
    <div><strong>Email:</strong> ${escapeHtml(order.customer.email || '-')}</div>
    <div><strong>Type:</strong> ${escapeHtml(order.type)}</div>
    <div><strong>Destination:</strong> ${escapeHtml(order.destination)}</div>
    ${order.note ? `<div><strong>Note:</strong> ${escapeHtml(order.note)}</div>` : ''}
  `;
  // items
  modalItems.innerHTML = '';
  order.items.forEach(it => {
    const row = document.createElement('div');
    row.className = 'flex justify-between items-center';
    row.innerHTML = `<div>${escapeHtml(it.name)} <span class="text-xs text-gray-500">x${it.qty}</span></div><div>₱${numberFormat(it.qty*it.price)}</div>`;
    modalItems.appendChild(row);
  });

  // status
  modalStatus.value = order.status;

  // receipt area
  receiptMeta.innerHTML = `<div><strong>Order:</strong> ${escapeHtml(order.orderNo)}</div>
    <div><strong>Placed:</strong> ${new Date(order.placedAt).toLocaleString()}</div>
    <div><strong>Customer:</strong> ${escapeHtml(order.customer.name)}</div>
    <div><strong>Destination:</strong> ${escapeHtml(order.destination)}</div>`;

  receiptItems.innerHTML = '';
  let sub = 0;
  order.items.forEach(it => {
    sub += it.price * it.qty;
    const r = document.createElement('div');
    r.className = 'flex justify-between';
    r.innerHTML = `<div>${escapeHtml(it.name)} <span class="text-xs text-gray-500">x${it.qty}</span></div><div>₱${numberFormat(it.qty*it.price)}</div>`;
    receiptItems.appendChild(r);
  });

  receiptSubtotal.innerText = `₱${numberFormat(sub)}`;
  receiptDelivery.innerText = `₱${numberFormat(order.deliveryFee || 0)}`;
  receiptTotal.innerText = `₱${numberFormat(sub + (order.deliveryFee||0))}`;

  // show the modal
  orderModal.classList.remove('hidden');

  // set modal action handlers
  document.getElementById('saveStatusBtn').onclick = () => {
    updateOrderStatus(order.orderNo, modalStatus.value);
  };
  document.getElementById('btnPrint').onclick = () => window.print();
  document.getElementById('btnQuickAccepted').onclick = () => updateOrderStatus(order.orderNo, 'Accepted');
  document.getElementById('btnQuickInProgress').onclick = () => updateOrderStatus(order.orderNo, 'In Progress');
  document.getElementById('btnQuickDeliver').onclick = () => updateOrderStatus(order.orderNo, 'To Deliver');
  document.getElementById('btnQuickComplete').onclick = () => updateOrderStatus(order.orderNo, 'Completed');
}

/* update status */
function updateOrderStatus(orderNo, newStatus) {
  const idx = orders.findIndex(o => o.orderNo === orderNo);
  if (idx === -1) return alert('Order not found');
  orders[idx].status = newStatus;
  saveOrders(orders);
  renderOrders();
  // refresh modal content for the same order
  openOrderModal(orderNo);
}

/* calculate total of order */
function calculateTotal(order) {
  const sub = order.items.reduce((s,it) => s + (it.qty * it.price),0);
  return sub + (order.deliveryFee || 0);
}

/* utilities */
function statusColorClass(status) {
  if (status === 'Pending') return 'text-red-600';
  if (status === 'Accepted') return 'text-green-600';
  if (status === 'In Progress') return 'text-yellow-600';
  if (status === 'To Deliver') return 'text-indigo-600';
  if (status === 'Completed') return 'text-gray-700';
  if (status === 'Aborted') return 'text-red-700';
  return 'text-gray-700';
}
function statusColorBackground(status) {
  if (status === 'Pending') return 'bg-red-100 text-red-700';
  if (status === 'Accepted') return 'bg-green-100 text-green-700';
  if (status === 'In Progress') return 'bg-yellow-100 text-yellow-700';
  if (status === 'To Deliver') return 'bg-indigo-100 text-indigo-700';
  if (status === 'Completed') return 'bg-gray-100 text-gray-700';
  if (status === 'Aborted') return 'bg-red-100 text-red-700';
  return 'bg-gray-100 text-gray-700';
}
function numberFormat(n){ return parseFloat(n||0).toLocaleString(undefined,{maximumFractionDigits:2}); }
function escapeHtml(s){ return String(s||'').replace(/[&<>"'`=\/]/g, ch => ({ "&":"&amp;","<":"&lt;",">":"&gt;",'"':"&quot;","'":"&#39;","/":"&#x2F;","`":"&#x60;","=":"&#x3D;" }[ch])); }

/* modal closing */
document.getElementById('closeOrderModal').addEventListener('click', () => { orderModal.classList.add('hidden'); });
orderModal.addEventListener('click', (e) => { if (e.target === orderModal) orderModal.classList.add('hidden'); });

/* New dummy order creation (for demo) */
document.getElementById('btnNewOrder').addEventListener('click', () => {
  const newOrdNo = 'ORD-' + new Date().toISOString().replace(/[:.-]/g,'').slice(0,15);
  const newOrder = {
    orderNo: newOrdNo,
    placedAt: new Date().toISOString(),
    customer: { name: 'Demo Customer', phone: '0917' + Math.floor(100000 + Math.random()*900000), email: 'demo@example.com' },
    type: Math.random()>0.5 ? 'Delivery' : 'Pickup',
    destination: Math.random()>0.5 ? 'Demo Address, City' : 'Pickup - Front Desk',
    items: [{ name: 'Chicken Pastil', qty: 1+Math.floor(Math.random()*3), price: 70 }],
    deliveryFee: Math.random()>0.5? 30:0,
    status: 'Pending',
    note: ''
  };
  orders.unshift(newOrder);
  saveOrders(orders);
  renderOrders();
});

/* reset demo */
document.getElementById('btnResetOrders').addEventListener('click', () => {
  if (!confirm('Reset demo orders to defaults?')) return;
  localStorage.removeItem(ORDERS_KEY);
  orders = loadOrders();
  renderOrders();
});

/* initial render */
renderOrders();
</script>
