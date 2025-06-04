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
}
