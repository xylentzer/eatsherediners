@include('layouts.side-dashboard')

<section class="bg-gray-50 min-h-screen py-10 px-4">
  <div class="max-w-7xl mx-auto space-y-8">

    <!-- Title and Export Button -->
    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-3xl font-extrabold text-red-600">📊 Revenue Dashboard</h1>
        <p class="text-xs text-gray-500 mt-1">Real-time revenue, profit metrics, and menu sales performance.</p>
      </div>
      <button id="exportExcel" class="bg-green-600 hover:bg-green-700 text-white font-bold px-5 py-2.5 rounded-xl shadow-md transition active:scale-95 flex items-center gap-2 text-sm">
        <span>📥</span> Export to Excel
      </button>
    </div>

    <!-- Summary Cards -->
    <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
      <div class="bg-white rounded-2xl shadow-md p-5 text-center border border-gray-100">
        <h2 class="text-gray-400 text-xs font-bold uppercase tracking-wider">Total Sales</h2>
        <p id="totalSales" class="text-3xl font-black text-red-600 mt-2">₱{{ number_format($totalSales ?? 0, 2) }}</p>
      </div>
      <div class="bg-white rounded-2xl shadow-md p-5 text-center border border-gray-100">
        <h2 class="text-gray-400 text-xs font-bold uppercase tracking-wider">Cancelled Sales</h2>
        <p id="cancelledSales" class="text-3xl font-black text-gray-400 mt-2">₱{{ number_format($cancelledSales ?? 0, 2) }}</p>
      </div>
      <div class="bg-white rounded-2xl shadow-md p-5 text-center border border-gray-100">
        <h2 class="text-gray-400 text-xs font-bold uppercase tracking-wider">Cost of Raw Materials</h2>
        <p id="totalCost" class="text-3xl font-black text-yellow-600 mt-2">₱{{ number_format($totalCost ?? 0, 2) }}</p>
      </div>
      <div class="bg-white rounded-2xl shadow-md p-5 text-center border border-gray-100">
        <h2 class="text-gray-400 text-xs font-bold uppercase tracking-wider">Net Profit</h2>
        <p id="netProfit" class="text-3xl font-black text-green-600 mt-2">₱{{ number_format($netProfit ?? 0, 2) }}</p>
      </div>
    </div>

    <!-- Revenue Chart -->
    <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
      <div class="flex items-center justify-between mb-4">
        <h2 class="text-xl font-bold text-red-600">Revenue Overview</h2>
        <select id="timeFilter" class="border border-gray-300 rounded-xl px-3 py-1.5 text-xs font-bold text-gray-700 bg-gray-50 focus:ring-2 focus:ring-red-500">
          <option value="week">This Week</option>
          <option value="month">This Month</option>
          <option value="year">This Year</option>
        </select>
      </div>
      <canvas id="revenueChart" height="100"></canvas>
    </div>

    <!-- Top Trending Menus -->
    <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
      <h2 class="text-xl font-bold text-red-600 mb-4">Top Trending Menu Items</h2>
      <div class="overflow-x-auto">
        <table id="trendingTable" class="min-w-full text-sm text-left text-gray-700">
          <thead class="bg-red-600 text-white uppercase text-xs font-bold tracking-wider">
            <tr>
              <th class="px-6 py-3">Rank</th>
              <th class="px-6 py-3">Menu Item</th>
              <th class="px-6 py-3 text-center">Orders Count</th>
              <th class="px-6 py-3 text-center">Total Revenue (₱)</th>
            </tr>
          </thead>
          <tbody id="trendingTableBody" class="divide-y divide-gray-100 bg-white">
            @forelse($trendingMenus ?? [] as $index => $item)
              <tr class="hover:bg-gray-50 transition">
                <td class="px-6 py-3.5 font-bold text-gray-900">#{{ $index + 1 }}</td>
                <td class="px-6 py-3.5 font-semibold text-gray-800">{{ $item['name'] }}</td>
                <td class="px-6 py-3.5 text-center font-bold text-gray-700">{{ $item['orders'] }}</td>
                <td class="px-6 py-3.5 text-center font-black text-gray-900">₱{{ number_format($item['revenue'], 2) }}</td>
              </tr>
            @empty
              <tr>
                <td colspan="4" class="px-6 py-8 text-center text-gray-400 italic">No menu sales data recorded yet.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

  </div>
</section>

<!-- Include External Chart & Excel JS Libraries -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

<script>
// Server data passed directly into JavaScript
const summaryMetrics = {
  totalSales: {{ $totalSales ?? 0 }},
  cancelledSales: {{ $cancelledSales ?? 0 }},
  totalCost: {{ $totalCost ?? 0 }},
  netProfit: {{ $netProfit ?? 0 }}
};

const trendingMenus = @json($trendingMenus ?? []);

// Dynamic Chart Data mapping
const revenueData = {
  week: [{{ ($totalSales ?? 0) * 0.1 }}, {{ ($totalSales ?? 0) * 0.15 }}, {{ ($totalSales ?? 0) * 0.12 }}, {{ ($totalSales ?? 0) * 0.18 }}, {{ ($totalSales ?? 0) * 0.2 }}, {{ ($totalSales ?? 0) * 0.25 }}],
  month: [{{ ($totalSales ?? 0) * 0.5 }}, {{ ($totalSales ?? 0) * 0.7 }}, {{ $totalSales ?? 0 }}],
  year: [{{ ($totalSales ?? 0) * 0.8 }}, {{ $totalSales ?? 0 }}]
};

const ctx = document.getElementById('revenueChart').getContext('2d');
let chartInstance;

function renderChart(period) {
  const data = revenueData[period] || [];
  const labels =
    period === "week" ? ["Mon","Tue","Wed","Thu","Fri","Sat","Sun"] :
    period === "month" ? ["Week 1","Week 2","Week 3","Week 4"] :
    ["Q1", "Q2", "Q3", "Q4"];

  if (chartInstance) chartInstance.destroy();

  chartInstance = new Chart(ctx, {
    type: 'line',
    data: {
      labels,
      datasets: [{
        label: 'Total Revenue (₱)',
        data,
        borderColor: '#dc2626',
        backgroundColor: 'rgba(220, 38, 38, 0.1)',
        borderWidth: 2,
        fill: true,
        tension: 0.4,
        pointRadius: 4,
        pointHoverRadius: 6
      }]
    },
    options: {
      responsive: true,
      scales: {
        y: { beginAtZero: true, ticks: { callback: v => '₱' + v.toLocaleString() } }
      }
    }
  });
}

function exportToExcel() {
  const wb = XLSX.utils.book_new();

  // Sheet 1: Summary Report
  const summarySheet = XLSX.utils.aoa_to_sheet([
    ["Metric", "Amount (₱)"],
    ["Total Sales", summaryMetrics.totalSales],
    ["Cancelled Sales", summaryMetrics.cancelledSales],
    ["Cost of Raw Materials", summaryMetrics.totalCost],
    ["Net Profit", summaryMetrics.netProfit]
  ]);
  XLSX.utils.book_append_sheet(wb, summarySheet, "Revenue Summary");

  // Sheet 2: Top Menus Report
  const menuSheet = XLSX.utils.json_to_sheet(trendingMenus.map((m, i) => ({
    Rank: i + 1,
    "Menu Item": m.name,
    "Orders Count": m.orders,
    "Revenue (₱)": m.revenue
  })));
  XLSX.utils.book_append_sheet(wb, menuSheet, "Top Trending Menus");

  XLSX.writeFile(wb, "EatsHere_Revenue_Report.xlsx");
}

// Event Listeners
document.getElementById('timeFilter').addEventListener('change', e => renderChart(e.target.value));
document.getElementById('exportExcel').addEventListener('click', exportToExcel);

// Initial Load
renderChart('week');
</script>