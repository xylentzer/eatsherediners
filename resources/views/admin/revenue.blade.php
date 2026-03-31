@include('layouts.side-dashboard')

<section class="bg-gray-50 min-h-screen py-10 px-4">
  <div class="max-w-7xl mx-auto space-y-8">

    <!-- Title and Export Button -->
    <div class="flex items-center justify-between">
      <h1 class="text-3xl font-bold text-red-600">Revenue Dashboard</h1>
      <button id="exportExcel" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg shadow">
        Export to Excel
      </button>
    </div>

    <!-- Summary Cards -->
    <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
      <div class="bg-white rounded-2xl shadow p-5 text-center">
        <h2 class="text-gray-500 text-sm uppercase">Total Sales</h2>
        <p id="totalSales" class="text-3xl font-bold text-red-600 mt-2">₱0</p>
      </div>
      <div class="bg-white rounded-2xl shadow p-5 text-center">
        <h2 class="text-gray-500 text-sm uppercase">Cancelled Sales</h2>
        <p id="cancelledSales" class="text-3xl font-bold text-gray-400 mt-2">₱0</p>
      </div>
      <div class="bg-white rounded-2xl shadow p-5 text-center">
        <h2 class="text-gray-500 text-sm uppercase">Cost of Raw Materials</h2>
        <p id="totalCost" class="text-3xl font-bold text-yellow-600 mt-2">₱0</p>
      </div>
      <div class="bg-white rounded-2xl shadow p-5 text-center">
        <h2 class="text-gray-500 text-sm uppercase">Net Profit</h2>
        <p id="netProfit" class="text-3xl font-bold text-green-600 mt-2">₱0</p>
      </div>
    </div>

    <!-- Chart -->
    <div class="bg-white rounded-2xl shadow p-6">
      <div class="flex items-center justify-between mb-4">
        <h2 class="text-xl font-semibold text-red-600">Revenue Overview</h2>
        <select id="timeFilter" class="border rounded-lg px-3 py-1 text-sm">
          <option value="week">This Week</option>
          <option value="month">This Month</option>
          <option value="year">This Year</option>
        </select>
      </div>
      <canvas id="revenueChart" height="120"></canvas>
    </div>

    <!-- Trending Menus -->
    <div class="bg-white rounded-2xl shadow p-6">
      <h2 class="text-xl font-semibold text-red-600 mb-4">Top Trending Menu Items</h2>
      <div class="overflow-x-auto">
        <table id="trendingTable" class="min-w-full text-sm text-left text-gray-700">
          <thead class="bg-red-600 text-white uppercase text-sm">
            <tr>
              <th class="px-6 py-3">Rank</th>
              <th class="px-6 py-3">Menu Item</th>
              <th class="px-6 py-3 text-center">Orders</th>
              <th class="px-6 py-3 text-center">Revenue (₱)</th>
            </tr>
          </thead>
          <tbody id="trendingTableBody" class="divide-y divide-gray-200 bg-white"></tbody>
        </table>
      </div>
    </div>

  </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script>
// Dummy data
const summaryData = {
  totalSales: 58230,
  cancelledSales: 2540,
  totalCost: 22800,
};

const trendingMenus = [
  { name: "Chicken Pastil (w/ Egg & Rice)", orders: 130, revenue: 9100 },
  { name: "Pork Tonkatsu", orders: 98, revenue: 17640 },
  { name: "Fried Chicken", orders: 112, revenue: 16800 },
  { name: "Beef Burger Steak", orders: 87, revenue: 12180 },
  { name: "Bangus (w/ Rice & Egg)", orders: 63, revenue: 8190 }
];

// Chart data
const revenueData = {
  week: [4200, 5600, 4800, 6200, 7100, 8500, 10300],
  month: [30000, 35000, 38000, 40000, 42000, 46000, 50000, 52000, 56000, 58000, 60000, 65000],
  year: [240000, 260000, 270000, 300000, 310000, 350000, 400000, 420000, 460000, 480000, 510000, 550000]
};

const ctx = document.getElementById('revenueChart').getContext('2d');
let chartInstance;

// Functions
function updateSummary() {
  const profit = summaryData.totalSales - summaryData.cancelledSales - summaryData.totalCost;
  document.getElementById('totalSales').textContent = `₱${summaryData.totalSales.toLocaleString()}`;
  document.getElementById('cancelledSales').textContent = `₱${summaryData.cancelledSales.toLocaleString()}`;
  document.getElementById('totalCost').textContent = `₱${summaryData.totalCost.toLocaleString()}`;
  document.getElementById('netProfit').textContent = `₱${profit.toLocaleString()}`;
}

function renderChart(period) {
  const data = revenueData[period];
  const labels =
    period === "week" ? ["Mon","Tue","Wed","Thu","Fri","Sat","Sun"] :
    period === "month" ? ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"] :
    ["2021","2022","2023","2024","2025","2026","2027","2028","2029","2030","2031","2032"];

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

function renderTrending() {
  const tbody = document.getElementById('trendingTableBody');
  tbody.innerHTML = '';
  trendingMenus.forEach((m, i) => {
    const tr = document.createElement('tr');
    tr.className = 'hover:bg-gray-50 transition';
    tr.innerHTML = `
      <td class="px-6 py-3 font-semibold text-gray-900">#${i + 1}</td>
      <td class="px-6 py-3">${m.name}</td>
      <td class="px-6 py-3 text-center">${m.orders}</td>
      <td class="px-6 py-3 text-center">₱${m.revenue.toLocaleString()}</td>
    `;
    tbody.appendChild(tr);
  });
}

function exportToExcel() {
  const wb = XLSX.utils.book_new();

  // Sheet 1: Summary
  const summarySheet = XLSX.utils.aoa_to_sheet([
    ["Metric", "Amount (₱)"],
    ["Total Sales", summaryData.totalSales],
    ["Cancelled Sales", summaryData.cancelledSales],
    ["Cost of Raw Materials", summaryData.totalCost],
    ["Net Profit", summaryData.totalSales - summaryData.cancelledSales - summaryData.totalCost],
  ]);
  XLSX.utils.book_append_sheet(wb, summarySheet, "Summary");

  // Sheet 2: Trending Menus
  const menuSheet = XLSX.utils.json_to_sheet(trendingMenus.map((m, i) => ({
    Rank: i + 1,
    "Menu Item": m.name,
    Orders: m.orders,
    "Revenue (₱)": m.revenue,
  })));
  XLSX.utils.book_append_sheet(wb, menuSheet, "Trending Menus");

  XLSX.writeFile(wb, "Revenue_Report.xlsx");
}

// Event listeners
document.getElementById('timeFilter').addEventListener('change', e => renderChart(e.target.value));
document.getElementById('exportExcel').addEventListener('click', exportToExcel);

// Initialize
updateSummary();
renderChart('week');
renderTrending();
</script>
