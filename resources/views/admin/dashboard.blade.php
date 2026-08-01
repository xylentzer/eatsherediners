@include('layouts.side-dashboard')

<div class="p-8 justify-center bg-gray-100 min-h-screen">

  <!-- Header -->
  <div class="flex justify-between items-center mb-6">
    <div>
      <h1 class="text-3xl font-bold text-gray-800">Dashboard Overview</h1>
      <p class="text-gray-500 text-sm">Welcome back, <span class="font-bold text-red-600">{{ Auth::user()->name }}</span>!</p>
    </div>

    <!-- Quick Logout Form -->
    <form method="POST" action="{{ route('admin.logout') }}">
      @csrf
      <button class="px-4 py-2 bg-red-600 text-white rounded-xl text-sm font-bold hover:bg-red-700 transition shadow">
        Log Out 🚪
      </button>
    </form>
  </div>

  <!-- Summary Cards -->
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    
    <!-- Total Orders Card -->
    <div class="bg-white shadow-lg rounded-2xl p-5 flex items-center justify-between border border-gray-100">
      <div>
        <h2 class="text-gray-500 text-xs font-bold uppercase tracking-wider">Total Orders</h2>
        <p class="text-3xl font-black text-gray-900 mt-1">
          {{ isset($orders) && is_countable($orders) ? count($orders) : 0 }}
        </p>
      </div>
      <div class="text-red-600 text-4xl bg-red-50 p-3 rounded-2xl">🛒</div>
    </div>

    <!-- Total Sales Card -->
    <div class="bg-white shadow-lg rounded-2xl p-5 flex items-center justify-between border border-gray-100">
      <div>
        <h2 class="text-gray-500 text-xs font-bold uppercase tracking-wider">Total Sales</h2>
        <p class="text-3xl font-black text-gray-900 mt-1">
          ₱{{ number_format(isset($orders) ? $orders->sum('total_amount') : 0, 2) }}
        </p>
      </div>
      <div class="text-green-600 text-4xl bg-green-50 p-3 rounded-2xl">💰</div>
    </div>

    <!-- Menu Items Card -->
    <div class="bg-white shadow-lg rounded-2xl p-5 flex items-center justify-between border border-gray-100">
      <div>
        <h2 class="text-gray-500 text-xs font-bold uppercase tracking-wider">Menu Items</h2>
        <p class="text-3xl font-black text-gray-900 mt-1">
          {{ \App\Models\Item::count() }}
        </p>
      </div>
      <div class="text-yellow-500 text-4xl bg-yellow-50 p-3 rounded-2xl">🍴</div>
    </div>

    <!-- Active Customers Card -->
    <div class="bg-white shadow-lg rounded-2xl p-5 flex items-center justify-between border border-gray-100">
      <div>
        <h2 class="text-gray-500 text-xs font-bold uppercase tracking-wider">Active Customers</h2>
        <p class="text-3xl font-black text-gray-900 mt-1">
          {{ isset($customers) && is_countable($customers) ? count($customers) : 0 }}
        </p>
      </div>
      <div class="text-blue-600 text-4xl bg-blue-50 p-3 rounded-2xl">👥</div>
    </div>
  </div>

  <!-- Chart Section -->
  <div class="bg-white shadow-lg rounded-2xl p-6 mb-8 border border-gray-100">
    <h2 class="text-xl font-bold text-gray-800 mb-4">Sales Overview</h2>
    <canvas id="salesChart" height="90"></canvas>
  </div>

  <!-- Recent Orders Table -->
  <div class="bg-white shadow-lg rounded-2xl p-6 border border-gray-100">
    <h2 class="text-xl font-bold text-gray-800 mb-4">Recent Orders</h2>
    <div class="overflow-x-auto">
      <table class="w-full border-collapse text-left text-sm text-gray-700">
        <thead>
          <tr class="bg-gray-50 text-gray-500 uppercase text-xs font-bold border-b">
            <th class="py-3 px-4">Order ID</th>
            <th class="py-3 px-4">Customer</th>
            <th class="py-3 px-4">Payment</th>
            <th class="py-3 px-4">Status</th>
            <th class="py-3 px-4">Total</th>
            <th class="py-3 px-4">Date</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
          @forelse(isset($orders) ? $orders->take(5) : [] as $order)
            <tr class="hover:bg-gray-50 transition">
              <td class="py-3 px-4 font-mono font-bold text-red-600">
                {{ str_starts_with($order->order_number, '#') ? $order->order_number : '#' . $order->order_number }}
              </td>
              <td class="py-3 px-4 font-semibold text-gray-900">{{ $order->customer_name }}</td>
              <td class="py-3 px-4 text-xs font-bold text-gray-600">{{ $order->payment_method }}</td>
              <td class="py-3 px-4">
                <span class="px-2.5 py-1 rounded-full text-xs font-bold 
                  {{ $order->status === 'Completed' ? 'bg-green-100 text-green-700' : '' }}
                  {{ $order->status === 'Pending' ? 'bg-red-100 text-red-700' : '' }}
                  {{ $order->status === 'In Progress' ? 'bg-yellow-100 text-yellow-700' : '' }}
                  {{ $order->status === 'To Deliver' ? 'bg-indigo-100 text-indigo-700' : '' }}
                  {{ $order->status === 'Aborted' ? 'bg-gray-100 text-gray-700' : '' }}">
                  {{ $order->status }}
                </span>
              </td>
              <td class="py-3 px-4 font-black text-gray-900">₱{{ number_format($order->total_amount, 2) }}</td>
              <td class="py-3 px-4 text-xs text-gray-500">{{ $order->created_at->format('M d, Y') }}</td>
            </tr>
          @empty
            <tr>
              <td colspan="6" class="py-6 text-center text-gray-400 italic">No recent orders recorded in database.</td>
            </tr>
          @endforelse
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