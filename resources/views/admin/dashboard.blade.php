 @include('layouts.side-dashboard')

<div class="mt-10 pl-12 justify-center bg-gray-100 min-h-screen">

  <!-- Header -->
  <h1 class="text-3xl font-bold text-gray-800 mb-6">Dashboard Overview</h1>

  <!-- Summary Cards -->
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white shadow-lg rounded-xl p-5 flex items-center justify-between">
      <div>
        <h2 class="text-gray-600 text-sm font-semibold">Total Orders</h2>
        <p class="text-2xl font-bold text-gray-900 mt-1">200</p>
      </div>
      <div class="text-red-600 text-4xl">🛒</div>
    </div>

    <div class="bg-white shadow-lg rounded-xl p-5 flex items-center justify-between">
      <div>
        <h2 class="text-gray-600 text-sm font-semibold">Total Sales</h2>
        <p class="text-2xl font-bold text-gray-900 mt-1">₱45,230</p>
      </div>
      <div class="text-green-600 text-4xl">💰</div>
    </div>

    <div class="bg-white shadow-lg rounded-xl p-5 flex items-center justify-between">
      <div>
        <h2 class="text-gray-600 text-sm font-semibold">Menu Items</h2>
        <p class="text-2xl font-bold text-gray-900 mt-1">32</p>
      </div>
      <div class="text-yellow-500 text-4xl">🍴</div>
    </div>

    <div class="bg-white shadow-lg rounded-xl p-5 flex items-center justify-between">
      <div>
        <h2 class="text-gray-600 text-sm font-semibold">Active Customers</h2>
        <p class="text-2xl font-bold text-gray-900 mt-1">89</p>
      </div>
      <div class="text-blue-600 text-4xl">👥</div>
    </div>
  </div>

  <!-- Chart Section -->
  <div class="bg-white shadow-lg rounded-xl p-6 mb-8">
    <h2 class="text-xl font-semibold text-gray-800 mb-4">Sales Overview</h2>
    <canvas id="salesChart" height="100"></canvas>
  </div>

  <!-- Recent Orders Table -->
  <div class="bg-white shadow-lg rounded-xl p-6">
    <h2 class="text-xl font-semibold text-gray-800 mb-4">Recent Orders</h2>
    <div class="overflow-x-auto">
      <table class="w-full border-collapse">
        <thead>
          <tr class="bg-gray-100 text-left text-gray-700 uppercase text-sm">
            <th class="py-3 px-4 border-b">Order ID</th>
            <th class="py-3 px-4 border-b">Customer</th>
            <th class="py-3 px-4 border-b">Type</th>
            <th class="py-3 px-4 border-b">Status</th>
            <th class="py-3 px-4 border-b">Total</th>
            <th class="py-3 px-4 border-b">Date</th>
          </tr>
        </thead>
        <tbody class="text-gray-700">
          <tr class="hover:bg-gray-50">
            <td class="py-3 px-4 border-b">#1023</td>
            <td class="py-3 px-4 border-b">Juan Dela Cruz</td>
            <td class="py-3 px-4 border-b">Bulk</td>
            <td class="py-3 px-4 border-b text-yellow-600 font-semibold">Preparing</td>
            <td class="py-3 px-4 border-b">₱1,560</td>
            <td class="py-3 px-4 border-b">Nov 2, 2025</td>
          </tr>
          <tr class="hover:bg-gray-50">
            <td class="py-3 px-4 border-b">#1024</td>
            <td class="py-3 px-4 border-b">Maria Santos</td>
            <td class="py-3 px-4 border-b">Individual</td>
            <td class="py-3 px-4 border-b text-green-600 font-semibold">Delivered</td>
            <td class="py-3 px-4 border-b">₱320</td>
            <td class="py-3 px-4 border-b">Nov 2, 2025</td>
          </tr>
          <tr class="hover:bg-gray-50">
            <td class="py-3 px-4 border-b">#1025</td>
            <td class="py-3 px-4 border-b">Carlos Ramos</td>
            <td class="py-3 px-4 border-b">Bulk</td>
            <td class="py-3 px-4 border-b text-red-600 font-semibold">Pending</td>
            <td class="py-3 px-4 border-b">₱2,130</td>
            <td class="py-3 px-4 border-b">Nov 1, 2025</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Chart.js Script -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  const ctx = document.getElementById('salesChart').getContext('2d');
  new Chart(ctx, {
    type: 'line',
    data: {
      labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
      datasets: [{
        label: 'Sales (₱)',
        data: [5000, 7200, 6600, 8500, 12000, 9300, 10400],
        borderColor: '#DC2626',
        backgroundColor: 'rgba(220, 38, 38, 0.1)',
        tension: 0.4,
        fill: true
      }]
    },
    options: {
      responsive: true,
      plugins: { legend: { display: false } },
      scales: {
        y: { beginAtZero: true, ticks: { color: '#374151' } },
        x: { ticks: { color: '#374151' } }
      }
    }
  });
</script>



<div class="min-h-screen flex flex-col items-center justify-center bg-gray-100">
    <h1 class="text-3xl font-bold mb-4">Welcome, {{ Auth::user()->name }}!</h1>
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">Log Out</button>
    </form>
</div>
