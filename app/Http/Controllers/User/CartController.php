<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = Cart::where('user_id', Auth::id())
            ->with('product')
            ->get();

        return response()->json([
            'items' => $cartItems,
            'total' => $cartItems->sum(function ($item) {
                return $item->product->price * $item->quantity;
            })
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $cartItem = Cart::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'product_id' => $request->product_id
            ],
            [
                'quantity' => $request->quantity
            ]
        );

        return response()->json([
            'message' => 'Product added to cart',
            'item' => $cartItem
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        Cart::where('user_id', Auth::id())
            ->where('product_id', $request->product_id)
            ->update(['quantity' => $request->quantity]);

        return response()->json(['message' => 'Cart updated']);
    }

    public function destroy(Request $request)
    {
        $request->validate(['product_id' => 'required|exists:products,id']);

        Cart::where('user_id', Auth::id())
            ->where('product_id', $request->product_id)
            ->delete();

        return response()->json(['message' => 'Item removed from cart']);
    }

    public function count()
    {
        $count = Cart::where('user_id', Auth::id())->count();
        return response()->json(['count' => $count]);
    }
}
