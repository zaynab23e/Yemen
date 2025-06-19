<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Models\Item;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        $orders = auth()->user()->orders()->with(['items', 'user'])->get();
        return OrderResource::collection($orders);
    }
    public function placeOrder(Request $request)
    {
        $user = Auth::user();
        $cart = $user->cart()->with('items')->first();

        if (!$cart || $cart->items->isEmpty()) {
            return response()->json(['message' => 'Cart is empty.'], 400);
        }

        DB::beginTransaction();

        try {
            // Create order
            $order = $user->orders()->create([
                'price' => $cart->items->sum('total_price'),
                'final_price' => $cart->items->sum('total_price') - $request->discount ?? 0,
                'discount' => $request->discount ?? 0,  
                'payment_method' => $request->payment_method ?? 'cash',
                'shipping_address' => $request->shipping_address ?? $user->address,
                'notes' => $request->notes ?? '',
                ]);

            // Create order items from cart items
            foreach ($cart->items as $cartItem) {
                // return $cartItem->total_price;
                $order->items()->create([
                    'item_id' => $cartItem->item_id,
                    'order_id' => $order->id,
                    'quantity' => $cartItem->quantity,
                    'price' => $cartItem->price,
                    'total_price' => $cartItem->total_price,
                ]);
            }

            // Optionally clear cart
            $cart->items()->delete();

            DB::commit();

            return response()->json([
                'message' => 'Order placed successfully.',
                'order_id' => $order->id,
                'order' => $order->load(['items', 'user']),
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Failed to place order.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


}