@include('layouts.side-dashboard')

<section class="bg-gray-50 min-h-screen py-10 px-4">
  <div class="max-w-7xl mx-auto">
    
    <!-- Header with Dynamic Customer Counter -->
    <div class="flex justify-between items-center mb-6">
      <div>
        <h1 class="text-3xl font-extrabold text-red-600">👥 Customer Records & Insights</h1>
        <p class="text-sm text-gray-500 mt-1">Real-time aggregated customer order data from your database.</p>
      </div>

      <span class="bg-red-100 text-red-600 font-bold px-4 py-2 rounded-xl text-sm shadow-sm">
        Total Customers: {{ isset($customers) && is_countable($customers) ? count($customers) : 0 }}
      </span>
    </div>

    <!-- Customer Insights Table -->
    <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden">
      <div class="overflow-x-auto">
        <table class="min-w-full text-sm text-left text-gray-700">
          <thead class="bg-red-600 text-white text-xs uppercase font-bold tracking-wider">
            <tr>
              <th class="px-6 py-4">Customer Name</th>
              <th class="px-6 py-4">Contact Info</th>
              <th class="px-6 py-4">Delivery Address</th>
              <th class="px-6 py-4 text-center">Total Orders</th>
              <th class="px-6 py-4 text-center">Last Order Date</th>
              <th class="px-6 py-4">Frequent Orders</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-200 bg-white">
            @forelse(isset($customers) ? $customers : [] as $customer)
              <tr class="hover:bg-gray-50 transition">
                <td class="px-6 py-4 font-bold text-gray-900 flex items-center gap-3">
                  <div class="w-8 h-8 rounded-full bg-red-100 text-red-600 flex items-center justify-center font-bold text-xs uppercase">
                    {{ strtoupper(substr($customer->name ?? 'C', 0, 1)) }}
                  </div>
                  {{ $customer->name ?? 'Guest Customer' }}
                </td>
                <td class="px-6 py-4 font-mono text-gray-600">{{ $customer->contact ?? 'N/A' }}</td>
                <td class="px-6 py-4 text-xs text-gray-500 max-w-xs truncate">{{ $customer->address ?? 'N/A' }}</td>
                <td class="px-6 py-4 text-center">
                  <span class="bg-gray-100 font-bold px-3 py-1 rounded-full text-xs text-gray-800">
                    {{ $customer->total_orders ?? 0 }} order(s)
                  </span>
                </td>
                <td class="px-6 py-4 text-center text-xs text-gray-500 font-medium">
                  {{ isset($customer->last_order_date) ? \Carbon\Carbon::parse($customer->last_order_date)->format('M d, Y • h:i A') : 'N/A' }}
                </td>
                <td class="px-6 py-4 font-medium text-xs text-gray-700">
                  {{ $customer->frequent_items ?? 'N/A' }}
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="6" class="px-6 py-12 text-center text-gray-400 italic">
                  No customer records found in the database.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

  </div>
</section>