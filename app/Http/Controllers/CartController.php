<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Display the cart.
     */
    public function index()
    {
        if (!Auth::check()) {
            // للضيوف، إظهار صفحة فارغة مع رسالة تسجيل دخول
            $cart = (object) [
                'id' => null,
                'items' => collect(),
                'total_amount' => 0,
            ];

            return view('cart.index', compact('cart'));
        }

        $cart = Cart::getActiveCartForUser(Auth::id());

        if (!$cart) {
            $cart = Cart::getOrCreateForUser(Auth::id());
        }

        $cart->load('items');

        return view('cart.index', compact('cart'));
    }

    /**
     * Add item to cart.
     */
    public function addItem(Request $request)
    {
        $request->validate([
            'service_type' => 'required|in:tour,hotel,car,space,boat,event,flight',
            'service_id' => 'required|integer',
            'price' => 'required|numeric|min:0',
            'quantity' => 'integer|min:1',
            'booking_data' => 'array|nullable',
        ]);

        $cart = Cart::getOrCreateForUser(Auth::id());

        // Check if item already exists
        $existingItem = $cart->items()
            ->where('service_type', $request->service_type)
            ->where('service_id', $request->service_id)
            ->first();

        if ($existingItem) {
            $existingItem->quantity += $request->get('quantity', 1);
            $existingItem->updateTotalPrice();
        } else {
            $quantity = $request->get('quantity', 1);
            $price = $request->price;

            CartItem::create([
                'cart_id' => $cart->id,
                'service_type' => $request->service_type,
                'service_id' => $request->service_id,
                'quantity' => $quantity,
                'price' => $price,
                'total_price' => round($quantity * $price, 2),
                'booking_data' => $request->booking_data,
            ]);

            $cart->updateTotalAmount();
        }

        return response()->json([
            'success' => true,
            'message' => __('Item added to cart successfully!'),
            'cart_count' => $cart->items()->count(),
        ]);
    }

    /**
     * Update cart item quantity.
     */
    public function updateItem(Request $request, CartItem $cartItem)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        // Check if user owns this cart item
        if ($cartItem->cart->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $cartItem->quantity = $request->quantity;
        $cartItem->updateTotalPrice();

        return response()->json([
            'success' => true,
            'message' => __('Cart updated successfully!'),
            'total_price' => $cartItem->total_price,
            'cart_total' => $cartItem->cart->total_amount,
        ]);
    }

    /**
     * Remove item from cart.
     */
    public function removeItem(CartItem $cartItem)
    {
        // Check if user owns this cart item
        if ($cartItem->cart->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $cart = $cartItem->cart;
        $cartItem->delete();
        $cart->updateTotalAmount();

        return response()->json([
            'success' => true,
            'message' => __('Item removed from cart successfully!'),
            'cart_total' => $cart->total_amount,
        ]);
    }

    /**
     * Clear the cart.
     */
    public function clear()
    {
        $cart = Cart::getActiveCartForUser(Auth::id());

        if ($cart) {
            $cart->items()->delete();
            $cart->updateTotalAmount();
        }

        return response()->json([
            'success' => true,
            'message' => __('Cart cleared successfully!'),
        ]);
    }

    /**
     * Remove cart item by id (for dropdown ajax)
     */
    public function removeItemById(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
        ]);
        $cart = Cart::getActiveCartForUser(Auth::id());
        if (!$cart) {
            return response()->json(['error' => 'Cart not found'], 404);
        }
        $item = $cart->items()->where('id', $request->id)->first();
        if (!$item) {
            return response()->json(['error' => 'Item not found'], 404);
        }
        $item->delete();
        $cart->updateTotalAmount();
        $cart_count = $cart->items()->count();
        return response()->json([
            'success' => true,
            'message' => __('Item removed from cart successfully!'),
            'cart_count' => $cart_count,
            'cart_total' => $cart->total_amount,
        ]);
    }

    /**
     * Get cart count for navbar.
     */
    public function getCount()
    {
        if (!Auth::check()) {
            return response()->json(['count' => 0]);
        }

        $cart = Cart::getActiveCartForUser(Auth::id());
        $count = $cart ? $cart->items()->count() : 0;

        return response()->json(['count' => $count]);
    }
}
