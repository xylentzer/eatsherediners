@include('layouts.header')

@php
session_start();

if (!isset($_SESSION['order'])) $_SESSION['order'] = [];

$step = $_POST['step'] ?? 'menu';

// Add item
if (isset($_POST['add_item'])) {
  $_SESSION['order'][] = [
    'name' => $_POST['item_name'],
    'price' => $_POST['item_price'],
    'qty' => $_POST['qty'],
    'side_dish' => $_POST['side_dish'] ?? null,
    'total' => $_POST['item_price'] * $_POST['qty']
  ];
  $step = 'summary';
}

// Remove item
if (isset($_POST['remove'])) {
  unset($_SESSION['order'][$_POST['remove']]);
  $_SESSION['order'] = array_values($_SESSION['order']);
  $step = 'summary';
}

// Proceed to details
if (isset($_POST['proceed'])) $step = 'details';

// Save customer details
if (isset($_POST['save_customer'])) {
  $_SESSION['customer'] = [
    'name' => $_POST['custName'],
    'email' => $_POST['custEmail'],
    'location' => $_POST['custLocation'],
    'time' => $_POST['custTime'],
    'date' => $_POST['custDate'],
    'payment' => $_POST['custPayment']
  ];
  $step = 'receipt';
}

// Reset session
if (isset($_POST['reset'])) {
  session_destroy();
  header("Location: /menu");
  exit;
}

// Menu data
$breakfasts = [
  ['name' => 'Tapsilog', 'price' => 109, 'description' => 'Beef tapa with garlic rice and fried egg.', 'image' => '/storage/tapsilog.jpg'],
  ['name' => 'Tocilog', 'price' => 109, 'description' => 'Sweet tocino with garlic rice and fried egg.', 'image' => '/storage/tocilog.jpg'],
  ['name' => 'Longsilog', 'price' => 109, 'description' => 'Pampanga-style longganisa with garlic rice and fried egg.', 'image' => '/storage/longsilog.jpg'],
  ['name' => 'Hotsilog', 'price' => 99, 'description' => 'Hotdog with garlic rice and fried egg.', 'image' => '/storage/hotsilog.jpg'],
  ['name' => 'Bangsilog', 'price' => 119, 'description' => 'Fried bangus with garlic rice and fried egg.', 'image' => '/storage/bangsilog.jpg'],
];

$main_courses = [
  ['name' => 'Pork Tonkatsu', 'price' => 129, 'description' => 'Pork Tonkatsu with sauce served with steamed rice and a side dish of your choice.', 'image' => '/storage/tonkatsu.jpg'],
  ['name' => 'Fried Chicken', 'price' => 149, 'description' => 'Fried chicken with steamed rice and a side dish of your choice.', 'image' => '/storage/friedchicken.jpg'],
  ['name' => 'Chicken ala King', 'price' => 149, 'description' => 'Breaded chicken breast with ala king sauce with steamed rice and a side dish of your choice.', 'image' => '/storage/Alaking.jpg'],
  ['name' => 'Beef Burger Steak', 'price' => 149, 'description' => '2 pcs beef burger steak with gravy, served with steamed rice and a side dish of your choice.', 'image' => '/storage/burgersteak.jpg'],
  ['name' => 'Fish Fillet in White Sauce', 'price' => 149, 'description' => 'Breaded cream dory with white sauce with steamed rice and a side dish of your choice.', 'image' => '/storage/fishfillet.jpg'],
  ['name' => 'Pork BBQ', 'price' => 159, 'description' => '2 sticks of pork BBQ with vinegar, steamed rice and a side dish of your choice.', 'image' => '/storage/BBQ.jpg'],
];

$addons = [
  ['Extra Rice', 14],
  ['Half Rice', 7],
  ['500ml Water', 14],
  ['300ml Water', 9],
];
@endphp

<style>
  /* === Smooth fade + slide animation === */
  .fade-in {
    animation: fadeInUp 0.6s ease-out;
  }
  @keyframes fadeInUp {
    0% { opacity: 0; transform: translateY(20px); }
    100% { opacity: 1; transform: translateY(0); }
  }

  /* Button hover animation */
  .btn-animate {
    transition: transform 0.2s ease-in-out, background-color 0.2s;
  }
  .btn-animate:hover {
    transform: scale(1.05);
  }
</style>

<div id="content" class="min-h-screen bg-gray-50 py-10 px-4 mt-10 flex flex-col items-center fade-in">

  {{-- === MENU === --}}
  @if($step === 'menu')
  <div class="max-w-5xl w-full mt-8 fade-in">
    <h2 class="text-3xl font-bold mb-6 text-gray-900 text-center">Menu</h2>

    {{-- Breakfast --}}
    <h3 class="text-2xl font-semibold mb-3 text-red-600">🍳 Breakfast</h3>
    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">
      @foreach($breakfasts as $meal)
      <form method="POST" class="bg-white shadow-md rounded-xl overflow-hidden transition duration-300 transform hover:scale-[1.02] hover:shadow-xl">
        @csrf
        <input type="hidden" name="item_name" value="{{ $meal['name'] }}">
        <input type="hidden" name="item_price" value="{{ $meal['price'] }}">
        <img src="{{ $meal['image'] }}" alt="{{ $meal['name'] }}" class="w-full h-48 object-cover transition duration-300 hover:brightness-105">
        <div class="p-4">
          <h3 class="font-bold text-lg">{{ $meal['name'] }} — ₱{{ $meal['price'] }}</h3>
          <p class="text-gray-600 text-sm mb-3">{{ $meal['description'] }}</p>
          <div class="flex items-center gap-3 mt-2">
            <label>Qty:</label>
            <input type="number" name="qty" value="1" min="1" class="w-20 border rounded-lg p-1 text-center">
            <button name="add_item" class="bg-red-600 text-white px-4 py-2 rounded-lg btn-animate">Add</button>
          </div>
        </div>
      </form>
      @endforeach
    </div>

    {{-- Main Course --}}
    <h3 class="text-2xl font-semibold mb-3 text-red-600">🍽️ Main Course</h3>
    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">
      @foreach($main_courses as $course)
      <form method="POST" class="bg-white shadow-md rounded-xl overflow-hidden transition duration-300 transform hover:scale-[1.02] hover:shadow-xl">
        @csrf
        <input type="hidden" name="item_name" value="{{ $course['name'] }}">
        <input type="hidden" name="item_price" value="{{ $course['price'] }}">
        <img src="{{ $course['image'] }}" alt="{{ $course['name'] }}" class="w-full h-48 object-cover transition duration-300 hover:brightness-105">
        <div class="p-4">
          <h3 class="font-bold text-lg">{{ $course['name'] }} — ₱{{ $course['price'] }}</h3>
          <p class="text-gray-600 text-sm mb-3">{{ $course['description'] }}</p>
          <label class="block mb-2">Side Dish:
            <select name="side_dish" class="w-full border rounded-lg p-2">
              <option>Lumpiang Togue</option>
              <option>Mixed Vegie</option>
              <option>Brownie Bite</option>
              <option>Butterscotch Bite</option>
            </select>
          </label>
          <div class="flex items-center gap-3 mt-2">
            <label>Qty:</label>
            <input type="number" name="qty" value="1" min="1" class="w-20 border rounded-lg p-1 text-center">
            <button name="add_item" class="bg-red-600 text-white px-4 py-2 rounded-lg btn-animate">Add</button>
          </div>
        </div>
      </form>
      @endforeach
    </div>

    {{-- Add-ons --}}
    <h3 class="text-2xl font-semibold mb-3 text-red-600">Add-ons</h3>
    <div class="grid sm:grid-cols-2 gap-4">
      @foreach($addons as [$name, $price])
      <form method="POST" class="bg-white shadow-md rounded-xl p-4 flex justify-between items-center hover:shadow-lg transition-all">
        @csrf
        <input type="hidden" name="item_name" value="{{ $name }}">
        <input type="hidden" name="item_price" value="{{ $price }}">
        <div>
          <p class="font-bold">{{ $name }}</p>
          <p class="text-gray-600">₱{{ $price }}</p>
        </div>
        <div class="flex items-center gap-2">
          <input type="number" name="qty" value="1" min="1" class="w-16 border rounded-lg p-1 text-center">
          <button name="add_item" class="bg-red-600 text-white px-3 py-1 rounded-lg btn-animate">Add</button>
        </div>
      </form>
      @endforeach
    </div>
  </div>
  @endif

  {{-- === ORDER SUMMARY === --}}
  @if($step === 'summary')
  <div class="max-w-3xl w-full bg-white shadow-lg rounded-2xl p-8 mt-8 fade-in">
    <h3 class="text-2xl font-bold mb-4">Order Summary</h3>
    @if(empty($_SESSION['order']))
      <p>No items yet.</p>
    @else
      @php $total = 0; @endphp
      @foreach($_SESSION['order'] as $i => $item)
        @php $total += $item['total']; @endphp
        <form method="POST" class="border-b py-3 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 hover:bg-gray-50 rounded-lg p-3 transition">
          @csrf
          <div>
            <p class="font-semibold">{{ $item['name'] }}</p>
            <p class="text-gray-600 text-sm">₱{{ number_format($item['price']) }} each</p>
            @if(isset($item['side_dish']))
              <p class="text-sm text-gray-500">Side Dish: {{ $item['side_dish'] }}</p>
            @endif
          </div>
          <button name="remove" value="{{ $i }}" class="bg-red-600 text-white px-3 py-1 rounded-lg btn-animate">Remove</button>
        </form>
      @endforeach
      <p class="mt-4 text-xl font-bold text-right">Total: ₱{{ number_format($total) }}</p>

      <div class="mt-6 flex justify-between">
        <form method="POST">
          @csrf
          <input type="hidden" name="step" value="menu">
          <button class="bg-gray-800 hover:bg-gray-900 text-white px-6 py-2 rounded-lg btn-animate">← Back to Menu</button>
        </form>

        <form method="POST">
          @csrf
          <button name="proceed" class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg btn-animate">Proceed →</button>
        </form>
      </div>
    @endif
  </div>
  @endif

  {{-- === DETAILS === --}}
  @if($step === 'details')
  <div class="max-w-3xl w-full bg-white shadow-lg rounded-2xl p-8 mt-8 fade-in">
    <h3 class="text-2xl font-bold mb-4 text-gray-900">Customer Details</h3>
    <form method="POST" class="space-y-4">
      @csrf
      <div>
        <label class="block font-semibold mb-1">Name:</label>
        <input type="text" name="custName" class="w-full border rounded-lg p-2" required>
      </div>

      <div>
        <label class="block font-semibold mb-1">Email:</label>
        <input type="email" name="custEmail" class="w-full border rounded-lg p-2" required>
      </div>

      <div>
        <label class="block font-semibold mb-1">Delivery Location:</label>
        <input type="text" name="custLocation" class="w-full border rounded-lg p-2" required>
      </div>

      <div>
        <label class="block font-semibold mb-1">Preferred Time:</label>
        <input type="time" name="custTime" class="w-full border rounded-lg p-2" required>
      </div>

       <div>
        <label class="block font-semibold mb-1">Preferred Date:</label>
        <input type="date" name="custDate" class="w-full border rounded-lg p-2" required>
      </div>

      <div>
        <label class="block font-semibold mb-1">Payment Method:</label>
        <select name="custPayment" class="w-full border rounded-lg p-2" required>
          <option value="Cash on Delivery">Cash on Delivery</option>
          <option value="GCash">GCash</option>
        </select>
      </div>

      <div class="flex justify-between mt-6">
        <form method="POST">
          @csrf
          <input type="hidden" name="step" value="summary">
          <button class="bg-gray-800 hover:bg-gray-900 text-white px-6 py-2 rounded-lg btn-animate">← Back</button>
        </form>

        <button name="save_customer" class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg btn-animate">
          Confirm Order →
        </button>
      </div>
    </form>
  </div>
  @endif

  {{-- === RECEIPT === --}}
  @if($step === 'receipt')
  <div class="max-w-3xl w-full bg-white shadow-lg rounded-2xl  p-20 mt-12 fade-in text-center">
    <h3 class="text-3xl font-bold text-green-600 mb-4">✅ Order Placed Successfully!</h3>
    <p class="text-gray-700 mb-6">Thank you, {{ $_SESSION['customer']['name'] ?? 'Customer' }}!</p>

    <p class="text-gray-600">A confirmation has been sent to <strong>{{ $_SESSION['customer']['email'] ?? 'your email' }}</strong>.</p>
    <p class="text-gray-600 mb-6">We'll deliver to <strong>{{ $_SESSION['customer']['location'] ?? 'your address' }}</strong> around <strong>{{ $_SESSION['customer']['time'] ?? 'your time' }}</strong>.</p>

    <form method="POST">
      @csrf
      <button name="reset" class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg btn-animate">
        Back to Menu
      </button>
    </form>
  </div>
  @endif

</div>

<script>
document.addEventListener("DOMContentLoaded", () => {
  const content = document.getElementById("content");
  content.classList.add("fade-in");
});
</script>

@include('layouts.footer')
