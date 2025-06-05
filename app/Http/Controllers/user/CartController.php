<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\cart;
use App\Models\CartItem;
use App\Models\Item;
use App\Models\Product;


class CartController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $cart = $user->cart()->first();

        if (!$cart) {
            return response()->json(['message' => 'السلة فارغة']);
        }

        $cartItems = CartItem::where('cart_id', $cart->id)->with('item')->get();

        return response()->json([
            'cart' => $cartItems,
            'total' => $cartItems->sum(fn($item) => $item->quantity * $item->price),
        ]);
    }
public function store(Request $request)
{
    $user = Auth::user();
    
    $request->validate([
        'item_id' => 'required|exists:items,id',
        'quantity' => 'required|integer|min:1',
    ]);

    $item = Item::findOrFail($request->item_id);
    $cart = Cart::firstOrCreate(['user_id' => $user->id]);

    $cartItem = CartItem::where('cart_id', $cart->id)
        ->where('item_id', $request->item_id)
        ->first();

    if ($cartItem) {
        $cartItem->update([
            'quantity' => $cartItem->quantity + $request->quantity,
        ]);
    } else {
        $cartItem = CartItem::create([
            'cart_id' => $cart->id,
            'item_id' => $request->item_id,
            'quantity' => $request->quantity,
            'price' => $item->price,
        ]);
    }

    return response()->json([
        'message' => 'تم إضافة المنتج إلى السلة',
        'cart_item' => $cartItem
    ]);
}


    public function update(Request $request, $itemId)
    {
        $request->validate(['quantity' => 'required|integer|min:1']);

        $user = Auth::user();
        $cart = Cart::where('user_id', $user->id)->first();

        if (!$cart) {
            return response()->json(['message' => 'السلة فارغة'], 404);
        }

        $cartItem = CartItem::where('cart_id', $cart->id)
            ->where('item_id', $itemId)
            ->first();

        if (!$cartItem) {
            return response()->json(['message' => 'العنصر غير موجود في السلة'], 404);
        }

        $cartItem->update(['quantity' => $request->quantity]);

        return response()->json(['message' => 'تم تحديث الكمية بنجاح']);
    }

    public function destroy($itemId)
    {
        $user = Auth::user();
        $cart = Cart::where('user_id', $user->id)->first();

        if (!$cart) {
            return response()->json(['message' => 'السلة فارغة'], 404);
        }

        $cartItem = CartItem::where('cart_id', $cart->id)
            ->where('item_id', $itemId)
            ->first();

        if (!$cartItem) {
            return response()->json(['message' => 'العنصر غير موجود في السلة'], 404);
        }

        $cartItem->delete();

        return response()->json(['message' => 'تم حذف المنتج من السلة']);
    }

    public function clearCart()
    {
        $user = Auth::user();
        $cart = Cart::where('user_id', $user->id)->where('status', 'pending')->first();

        if ($cart) {
            CartItem::where('cart_id', $cart->id)->delete();
        }

        return response()->json(['message' => 'تم مسح محتويات السلة']);
    }
}

