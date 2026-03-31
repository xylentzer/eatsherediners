@include('layouts.side-dashboard')

<section class="bg-gray-50 min-h-screen py-10 px-4">
  <div class="max-w-7xl mx-auto">
    <h1 class="text-3xl font-bold text-red-600 mb-6">Customer Records & Insights</h1>

    <div class="bg-white rounded-2xl shadow overflow-x-auto">
      <table class="min-w-full text-sm text-left text-gray-700">
        <thead class="bg-red-600 text-white text-sm uppercase">
          <tr>
            <th class="px-6 py-3">Customer Name</th>
            <th class="px-6 py-3">Email</th>
            <th class="px-6 py-3">Phone</th>
            <th class="px-6 py-3 text-center">Total Orders</th>
            <th class="px-6 py-3 text-center">Average Rating</th>
            <th class="px-6 py-3 text-center">Last Order</th>
            <th class="px-6 py-3">Frequent Orders</th>
          </tr>
        </thead>
        <tbody id="customerTableBody" class="divide-y divide-gray-200 bg-white"></tbody>
      </table>
    </div>
  </div>
</section>

<script>
// Demo customer data
const CUSTOMERS = [
  {
    name: "Juan Dela Cruz",
    email: "juan@example.com",
    phone: "09171234567",
    totalOrders: 12,
    lastOrder: "2025-10-30 14:32",
    avgRating: 4.6,
    frequentOrders: "Chicken Pastil, Fried Chicken, Pork Tapa"
  },
  {
    name: "Maria Santos",
    email: "maria@example.com",
    phone: "09991234567",
    totalOrders: 8,
    lastOrder: "2025-10-28 09:15",
    avgRating: 4.9,
    frequentOrders: "Bangus w/ Rice, Chicken Ala King"
  },
  {
    name: "Carlos Mendoza",
    email: "carlos@example.com",
    phone: "09281231234",
    totalOrders: 5,
    lastOrder: "2025-10-25 18:22",
    avgRating: 4.3,
    frequentOrders: "Pork BBQ, Extra Rice, Water 500ml"
  },
  {
    name: "Ana Reyes",
    email: "ana@example.com",
    phone: "09183456789",
    totalOrders: 10,
    lastOrder: "2025-11-01 10:45",
    avgRating: 4.8,
    frequentOrders: "Pork Tonkatsu, Chicken Ala King"
  }
];

// Render table
const tbody = document.getElementById('customerTableBody');
function renderCustomers() {
  tbody.innerHTML = '';
  CUSTOMERS.forEach(c => {
    const tr = document.createElement('tr');
    tr.className = 'hover:bg-gray-50 transition';
    tr.innerHTML = `
      <td class="px-6 py-4 font-semibold text-gray-900">${c.name}</td>
      <td class="px-6 py-4">${c.email}</td>
      <td class="px-6 py-4">${c.phone}</td>
      <td class="px-6 py-4 text-center">${c.totalOrders}</td>
      <td class="px-6 py-4 text-center">${c.avgRating.toFixed(1)} ⭐</td>
      <td class="px-6 py-4 text-center">${c.lastOrder}</td>
      <td class="px-6 py-4">${c.frequentOrders}</td>
    `;
    tbody.appendChild(tr);
  });
}
renderCustomers();
</script>
