<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use Illuminate\Http\Request;

class CartAdminController extends Controller
{
    /**
     * Display all carts.
     */
    public function index(Request $request)
    {
        $query = Cart::with(['user', 'items'])
            ->orderBy('created_at', 'desc');

        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        // Search by user
        if ($request->has('search') && $request->search !== '') {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('first_name', 'like', '%'.$request->search.'%')
                    ->orWhere('last_name', 'like', '%'.$request->search.'%')
                    ->orWhere('email', 'like', '%'.$request->search.'%');
            });
        }

        $carts = $query->paginate(20);

        $data = [
            'page_title' => __('Shopping Carts Management'),
            'carts' => $carts,
            'request' => $request,
        ];

        return view('admin.cart.index', $data);
    }

    /**
     * Show cart details.
     */
    public function show(Cart $cart)
    {
        $cart->load(['user', 'items']);

        $data = [
            'page_title' => __('Cart Details'),
            'cart' => $cart,
        ];

        return view('admin.cart.show', $data);
    }

    /**
     * Update cart status.
     */
    public function updateStatus(Request $request, Cart $cart)
    {
        $request->validate([
            'status' => 'required|in:active,completed,abandoned',
        ]);

        $cart->update(['status' => $request->status]);

        return redirect()->back()->with('success', __('Cart status updated successfully!'));
    }

    /**
     * Delete a cart.
     */
    public function destroy(Cart $cart)
    {
        $cart->items()->delete();
        $cart->delete();

        return redirect()->route('admin.carts.index')
            ->with('success', __('Cart deleted successfully!'));
    }

    /**
     * Get cart statistics for dashboard.
     */
    public function getStats()
    {
        $stats = [
            'total_carts' => Cart::count(),
            'active_carts' => Cart::where('status', 'active')->count(),
            'completed_carts' => Cart::where('status', 'completed')->count(),
            'abandoned_carts' => Cart::where('status', 'abandoned')->count(),
            'total_value' => Cart::where('status', 'active')->sum('total_amount'),
        ];

        return response()->json($stats);
    }
}
