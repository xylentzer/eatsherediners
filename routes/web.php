<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\TrackController;
use App\Http\Controllers\ItemController;
use App\Models\Item;
use App\Models\Order;

Route::middleware('auth')->prefix('admin')->group(function () {

    Route::get('/revenue', function () {
        // Fetch all orders with their items
        $orders = Order::with('items')->get();

        // 1. Calculate Metrics
        $totalSales = $orders->where('status', '!=', 'Aborted')->sum('total_amount');
        $cancelledSales = $orders->where('status', 'Aborted')->sum('total_amount');
        
        // Estimated Cost of Raw Materials (default ~35% of total sales if no inventory model exists)
        $totalCost = $totalSales * 0.35; 
        $netProfit = $totalSales - $totalCost;

        // 2. Aggregate Trending Menu Items
        $itemStats = [];
        foreach ($orders->where('status', '!=', 'Aborted') as $order) {
            foreach ($order->items as $item) {
                if (!isset($itemStats[$item->item_name])) {
                    $itemStats[$item->item_name] = [
                        'name'    => $item->item_name,
                        'orders'  => 0,
                        'revenue' => 0,
                    ];
                }
                $itemStats[$item->item_name]['orders'] += $item->quantity;
                $itemStats[$item->item_name]['revenue'] += $item->subtotal;
            }
        }

        // Sort items by revenue descending
        usort($itemStats, fn($a, $b) => $b['revenue'] <=> $a['revenue']);
        $trendingMenus = array_slice($itemStats, 0, 5);

        return view('admin.revenue', compact('totalSales', 'cancelledSales', 'totalCost', 'netProfit', 'trendingMenus'));
    })->name('admin.revenue');

});

/*
|--------------------------------------------------------------------------
| Customer Public Routes
|--------------------------------------------------------------------------
*/

Route::view('/', 'home')->name('home');
Route::view('/home', 'home');
Route::view('/about', 'about')->name('about');
Route::view('/contact', 'contact')->name('contact');

// Menu Route (Fetches dynamic menu items)
Route::get('/test', function () {
    $items = Item::all(); 
    return view('test', compact('items')); 
})->name('test');

/*
|--------------------------------------------------------------------------
| Admin Guest Routes
|--------------------------------------------------------------------------
*/

Route::redirect('/admin', '/admin/login');

Route::controller(LoginController::class)->prefix('admin')->group(function () {
    Route::get('/login', 'showLoginForm')->name('login'); 
    Route::post('/login', 'login')->name('admin.login.submit');
});

Route::controller(RegisterController::class)->prefix('admin')->group(function () {
    Route::get('/register', 'showRegisterForm')->name('admin.register');
    Route::post('/register', 'register')->name('admin.register.submit');
});

/*
|--------------------------------------------------------------------------
| Admin Protected Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->prefix('admin')->group(function () {

    Route::post('/logout', [LoginController::class, 'logout'])->name('admin.logout');

    // Dashboard Route with dynamic data
    Route::get('/dashboard', function () {
        $orders = Order::with('items')->latest()->get();

        $customers = $orders->groupBy('customer_name')->map(function ($group) {
            $lastOrder = $group->first();
            $itemCounts = [];
            foreach ($group as $order) {
                foreach ($order->items as $item) {
                    $itemCounts[$item->item_name] = ($itemCounts[$item->item_name] ?? 0) + $item->quantity;
                }
            }
            arsort($itemCounts);
            $topItems = array_slice(array_keys($itemCounts), 0, 3);

            return (object) [
                'name'            => $lastOrder->customer_name,
                'contact'         => $lastOrder->customer_contact,
                'address'         => $lastOrder->delivery_address,
                'total_orders'    => $group->count(),
                'last_order_date' => $lastOrder->created_at,
                'frequent_items'  => !empty($topItems) ? implode(', ', $topItems) : 'N/A',
            ];
        });

        return view('admin.dashboard', compact('orders', 'customers'));
    })->name('admin.dashboard');

    // Customer Records & Insights Route
    Route::get('/customer', function () {
        $orders = Order::with('items')->latest()->get();

        $customers = $orders->groupBy('customer_name')->map(function ($group) {
            $lastOrder = $group->first();
            $itemCounts = [];
            foreach ($group as $order) {
                foreach ($order->items as $item) {
                    $itemCounts[$item->item_name] = ($itemCounts[$item->item_name] ?? 0) + $item->quantity;
                }
            }
            arsort($itemCounts);
            $topItems = array_slice(array_keys($itemCounts), 0, 3);

            return (object) [
                'name'            => $lastOrder->customer_name,
                'contact'         => $lastOrder->customer_contact,
                'address'         => $lastOrder->delivery_address,
                'total_orders'    => $group->count(),
                'last_order_date' => $lastOrder->created_at,
                'frequent_items'  => !empty($topItems) ? implode(', ', $topItems) : 'N/A',
            ];
        });

        return view('admin.customer', compact('customers'));
    })->name('admin.customer');

    // Dynamic Orders Route
    Route::get('/order', function () {
        $orders = Order::with('items')->latest()->get();
        return view('admin.order', compact('orders'));
    })->name('admin.order');

    // Order Status Update Route
    Route::patch('/order/{id}/status', function (Request $request, $id) {
        $request->validate([
            'status' => 'required|string'
        ]);

        $order = Order::findOrFail($id);
        $order->status = $request->status;
        $order->save();

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully!',
            'status'  => $order->status
        ]);
    })->name('admin.order.updateStatus');

    Route::view('/inventory', 'admin.inventory')->name('admin.inventory');
    Route::view('/revenue', 'admin.revenue')->name('admin.revenue');

    // Item Management Routes
    Route::get('/item', [ItemController::class, 'index'])->name('admin.item');
    Route::post('/item', [ItemController::class, 'store'])->name('admin.item.store');
    Route::put('/item/{id}', [ItemController::class, 'update'])->name('admin.item.update');
    Route::delete('/item/{id}', [ItemController::class, 'destroy'])->name('admin.item.destroy');
});

/*
|--------------------------------------------------------------------------
| Cart Routes
|--------------------------------------------------------------------------
*/

Route::controller(CartController::class)->group(function () {
    Route::get('/cart', 'index')->name('cart');
    Route::post('/cart/add', 'addToCart')->name('cart.add');
    Route::patch('/cart/{id}/increase', 'increase')->name('cart.increase');
    Route::patch('/cart/{id}/decrease', 'decrease')->name('cart.decrease');
    Route::delete('/cart/{id}', 'remove')->name('cart.remove');
    Route::post('/checkout/process', 'processCheckout')->name('checkout.process');
});

/*
|--------------------------------------------------------------------------
| Track Order Routes
|--------------------------------------------------------------------------
*/

Route::controller(TrackController::class)->prefix('track')->group(function () {
    Route::get('/', 'index')->name('track');
    Route::post('/search', 'search')->name('track.search');
    
    // Redirect direct GET access on /track/search back to /track
    Route::get('/search', function () {
        return redirect()->route('track');
    });
});