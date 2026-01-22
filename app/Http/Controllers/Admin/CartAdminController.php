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
    public function show(Request $request, Cart $cart)
    {
        $cart->load(['user', 'items']);

        // If AJAX request, return JSON
        if ($request->wantsJson() || $request->ajax()) {
            $items = $cart->items->map(function ($item) {
                $service = $item->service;
                $bookingData = $item->booking_data ?? [];

                // Get service title
                $title = 'N/A';
                if ($service) {
                    $title = $service->title ?? $service->name ?? 'Service #'.$item->service_id;
                }

                // Get subtitle (service type)
                $subtitle = ucfirst($item->service_type ?? 'Service');

                // Get category
                $category = '';
                if ($service) {
                    if ($item->service_type === 'tour' && isset($service->category_id)) {
                        $tourCategory = \Modules\Tour\Models\TourCategory::find($service->category_id);
                        $category = $tourCategory ? $tourCategory->name : '';
                    }
                }

                // Get image
                $image = asset('images/placeholder.jpg');
                if ($service && isset($service->image_id)) {
                    $image = get_file_url($service->image_id, 'medium') ?? $image;
                } elseif ($service && isset($service->banner_image_id)) {
                    $image = get_file_url($service->banner_image_id, 'medium') ?? $image;
                }

                // Get booking datetime
                $datetime = '';
                if (isset($bookingData['start_date'])) {
                    $date = date('Y-m-d', strtotime($bookingData['start_date']));
                    $dayName = date('l', strtotime($date)); // Get day name in English
                    $datetime = $dayName.', '.date('d/m/Y', strtotime($date));

                    // Add time if available
                    if (isset($bookingData['start_time'])) {
                        $datetime .= ' '.$bookingData['start_time'];
                    } elseif (strpos($bookingData['start_date'], ':') !== false) {
                        $datetime .= ' '.date('H:i', strtotime($bookingData['start_date']));
                    }
                }

                // Get quantity text from person_types
                $quantityText = [];
                if (isset($bookingData['person_types']) && is_array($bookingData['person_types'])) {
                    foreach ($bookingData['person_types'] as $personType) {
                        if (isset($personType['number']) && $personType['number'] > 0) {
                            $name = $personType['name'] ?? 'Person';
                            $quantityText[] = $name.' x'.$personType['number'];
                        }
                    }
                } elseif (isset($bookingData['adults']) && $bookingData['adults'] > 0) {
                    $quantityText[] = 'Adult x'.$bookingData['adults'];
                    if (isset($bookingData['children']) && $bookingData['children'] > 0) {
                        $quantityText[] = 'Child x'.$bookingData['children'];
                    }
                } elseif (isset($bookingData['total_guests']) && $bookingData['total_guests'] > 0) {
                    $quantityText[] = 'Guests: '.$bookingData['total_guests'];
                } elseif ($item->quantity > 0) {
                    $quantityText[] = 'Quantity: '.$item->quantity;
                }

                return [
                    'id' => $item->id,
                    'title' => $title,
                    'subtitle' => $subtitle,
                    'category' => $category,
                    'image' => $image,
                    'datetime' => $datetime,
                    'quantity_text' => $quantityText,
                    'price' => number_format($item->price, 2).get_current_currency_svg(),
                    'total' => number_format($item->total_price, 2).get_current_currency_svg(),
                ];
            });

            return response()->json([
                'success' => true,
                'items' => $items,
                'cart' => [
                    'id' => $cart->id,
                    'total' => $cart->total_amount,
                    'items_count' => $cart->items->count(),
                ],
            ]);
        }

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
