<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Display a listing of the carts.
     */
    public function index(Request $request)
    {
        $query = Cart::with(['user', 'items']);

        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        // Search by user name or email
        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $carts = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.cart.index', compact('carts'));
    }

    /**
     * Display the specified cart.
     */
    public function show(Cart $cart)
    {
        $cart->load(['user', 'items']);

        return view('admin.cart.show', compact('cart'));
    }

    /**
     * Update the specified cart status.
     */
    public function update(Request $request, Cart $cart)
    {
        $request->validate([
            'status' => 'required|in:active,completed,abandoned',
        ]);

        $cart->update(['status' => $request->status]);

        return back()->with('success', __('Cart status updated successfully!'));
    }

    /**
     * Remove the specified cart from storage.
     */
    public function destroy(Cart $cart)
    {
        $cart->delete();

        return redirect()->route('admin.carts.index')->with('success', __('Cart deleted successfully!'));
    }
}
