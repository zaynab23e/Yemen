<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use Illuminate\Http\Request;

class AdminOrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['user', 'items'])->latest()->paginate(10); // Or use ->get() if you don’t want pagination
        return response()->json(
            [
                'status' => 'success',
                'message' => 'Orders retrieved successfully.',
                'data' => OrderResource::collection($orders),
            ],
            200
        );
    }
    public function show($id)
    {
        $order = Order::with(['user', 'items'])->find($id); // Or use ->get() if you don’t want pagination
        if (!$order) {
            return response()->json([
                'status' => 'Error has occurred...',
                'message' => 'No Order Found',
                'data' => null
            ], 500);
        }
        return new OrderResource($order);
    }    
}
