<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item; // Added missing Item model import
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    /**
     * Display the Shopping Cart View
     */
    public function index()
    {
        return view('cart'); // Render cart.blade.php
    }

    /**
     * Add Item to Cart (AJAX & Standard Form Compatible)
     */
    public function addToCart(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:items,id',
        ]);

        $item = Item::findOrFail($request->item_id);

        $cart = session()->get('cart', []);

        // Format the image path properly (handles external URLs, relative storage paths, & fallbacks)
        $imageUrl = $item->image
            ? (str_starts_with($item->image, 'http') ? $item->image : asset('storage/' . $item->image))
            : asset('storage/default.jpg');

        if (isset($cart[$item->id])) {
            $cart[$item->id]['qty'] += 1;
            $cart[$item->id]['image'] = $imageUrl; // Ensure session updates image path
        } else {
            $cart[$item->id] = [
                'name'  => $item->name,
                'price' => $item->price,
                'qty'   => 1,
                'image' => $imageUrl
            ];
        }

        session()->put('cart', $cart);

        return redirect()->back()->with('success', 'Item added to cart!');
    }

    /**
     * Increase Item Quantity
     */
    public function increase($id)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            $cart[$id]['qty'] += 1;
            session()->put('cart', $cart);
        }

        return redirect()->back();
    }

    /**
     * Decrease Item Quantity
     */
    public function decrease($id)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            if ($cart[$id]['qty'] > 1) {
                $cart[$id]['qty'] -= 1;
            } else {
                unset($cart[$id]);
            }
            session()->put('cart', $cart);
        }

        return redirect()->back();
    }

    /**
     * Remove Item from Cart
     */
    public function remove($id)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }

        return redirect()->back();
    }

    /**
     * Process Order Checkout & Save to Database
     */
    public function processCheckout(Request $request)
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return response()->json([
                'success' => false,
                'message' => 'Your cart is empty. Please add items before checking out.'
            ], 400);
        }

        // Validate incoming request fields
        $validator = Validator::make($request->all(), [
            'custName'    => 'required|string|max:255',
            'custContact' => 'required|string|max:255',
            'custAddress' => 'required|string|max:500',
            'custTime'    => 'nullable|string',
            'custNotes'   => 'nullable|string',
            'custPayment' => 'required|string',
            'gcashRefNo'  => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        try {
            DB::beginTransaction();

            // Calculate Grand Total
            $grandTotal = 0;
            foreach ($cart as $item) {
                $grandTotal += $item['price'] * $item['qty'];
            }

            // Generate Order Tracking Number
            $orderNumber = '#ESH-' . strtoupper(substr(uniqid(), -6));

            // Create Order Record
            $order = Order::create([
                'order_number'     => $orderNumber,
                'customer_name'    => $request->custName,
                'customer_contact' => $request->custContact,
                'delivery_address' => $request->custAddress,
                'preferred_time'   => $request->custTime ?? null,
                'special_notes'    => $request->custNotes ?? null,
                'payment_method'   => $request->custPayment,
                'gcash_ref_no'     => $request->gcashRefNo ?? null,
                'total_amount'     => $grandTotal,
                'status'           => 'Pending',
            ]);

            // Create Order Items Records
            foreach ($cart as $item) {
                OrderItem::create([
                    'order_id'  => $order->id,
                    'item_name' => $item['name'],
                    'price'     => $item['price'],
                    'quantity'  => $item['qty'],
                    'subtotal'  => $item['price'] * $item['qty'],
                ]);
            }

            DB::commit();

            // Clear Cart Session
            session()->forget('cart');

            return response()->json([
                'success'      => true,
                'order_number' => $order->order_number,
                'message'      => 'Order submitted successfully!'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Checkout Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Database error: ' . $e->getMessage()
            ], 500);
        }
    }
}
