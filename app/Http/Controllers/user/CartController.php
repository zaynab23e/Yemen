<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;


class CartController extends Controller
{
     
    public function index()
    {
        $user = Auth::user();
        $cart = Cart::where('user_id', $user->id)->first();

        if (!$cart) {
            return response()->json(['message' => 'السلة فارغة']);
        }

        $cartItems = CartItem::where('cart_id', $cart->id)->with('product')->get();

        return response()->json([
            'cart' => $cartItems,
            'total' => $cartItems->sum('total_price')
        ]);
    }

    
    public function store(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        // $product = Product::findOrFail($request->product_id);
        $cart = Cart::where('user_id', $user->id)->where('status', 'pending')->first();

        if (!$cart) {
            $cart = Cart::create(['user_id' => $user->id]);
        }

        $cartItem = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $request->product_id)
            ->first();

        if ($cartItem) {
            $cartItem->update([
                'quantity' => $cartItem->quantity + $request->quantity,
            ]);
        } else {
            $cartItem = CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
                // 'price' => $product->price,
            ]);
        }

        return response()->json([
            'message' => 'تم إضافة المنتج إلى السلة',
            'cart_item' => $cartItem
        ]);
    }
    
    
    
    public function update(Request $request, $productId)
{
    $request->validate(['quantity' => 'required|integer|min:1']);

    $user = Auth::user();
    $cart = Cart::where('user_id', $user->id)->where('status', 'pending')->first();

    if (!$cart) {
        return response()->json(['message' => 'السلة فارغة'], 404);
    }

    $cartItem = CartItem::where('cart_id', $cart->id)
                        ->where('product_id', $productId)
                        ->first();

    if (!$cartItem) {
        return response()->json(['message' => 'العنصر غير موجود في السلة'], 404);
    }

    $cartItem->update(['quantity' => $request->quantity]);

    return response()->json(['message' => 'تم تحديث الكمية بنجاح']);
}



    //   public function update(Request $request, $cartItemId)
    // {
    //     $request->validate(['quantity' => 'required|integer|min:1']);
    
    //     $cartItem = CartItem::find($cartItemId);
    
    //     if (!$cartItem) {
    //         return response()->json(['message' => 'العنصر غير موجود في السلة'], 404);
    //     }
    
    //     $cartItem->update(['quantity' => $request->quantity]);
    
    //     return response()->json(['message' => 'تم تحديث الكمية بنجاح']);
    // }
    

      
    // public function destroy($cartItemId)
    // {
    //     $cartItem = CartItem::findOrFail($cartItemId);
    //     $cartItem->delete();

    //     return response()->json(['message' => 'تم حذف المنتج من السلة']);
    // }
    
 
  
public function destroy($productId)
{
    $user = Auth::user();
    $cart = Cart::where('user_id', $user->id)->first();

    if (!$cart) {
        return response()->json(['message' => 'السلة فارغة'], 404);
    }

    $cartItem = CartItem::where('cart_id', $cart->id)
                        ->where('product_id', $productId)
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


