<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class TrackController extends Controller
{
    public function index()
    {
        return view('track');
    }

    public function search(Request $request)
    {
        $request->validate([
            'order_number' => 'required|string'
        ]);

        $orderNumber = trim($request->order_number);

        // Ensure hashtag prefix is handled consistently
        if (!str_starts_with($orderNumber, '#')) {
            $orderNumber = '#' . $orderNumber;
        }

        $order = Order::with('items')->where('order_number', $orderNumber)->first();

        if (!$order) {
            // FIX: Explicitly redirect to the GET route 'track' with session error
            return redirect()->route('track')->with('error', 'Order number not found :(');
        }

        return view('track', compact('order'));
    }
}