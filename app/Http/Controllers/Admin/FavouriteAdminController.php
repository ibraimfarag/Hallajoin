<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\User\Models\UserWishList;

class FavouriteAdminController extends Controller
{
    /**
     * Display a listing of favourites grouped by user.
     */
    public function index(Request $request)
    {
        // Get users who have favourites with their favourites count
        $query = \App\User::select('users.*')
            ->join('user_wishlist', 'users.id', '=', 'user_wishlist.user_id')
            ->selectRaw('COUNT(user_wishlist.id) as favourites_count')
            ->selectRaw('MAX(user_wishlist.created_at) as last_added')
            ->groupBy('users.id')
            ->orderBy('last_added', 'desc');

        $users = $query->paginate(20);

        $data = [
            'page_title' => __('Favourites'),
            'users' => $users,
        ];

        return view('admin.favourite.index', $data);
    }

    /**
     * Show user's all favourites.
     */
    public function show(Request $request, $userId)
    {
        // Get all favourites for this user
        $favourites = UserWishList::with(['service'])
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();

        // If AJAX request, return JSON
        if ($request->wantsJson() || $request->ajax()) {
            $items = $favourites->map(function ($favourite) {
                $service = $favourite->service;

                if (! $service) {
                    return null;
                }

                // Get service title
                $title = $service->title ?? $service->name ?? 'N/A';

                // Get subtitle (service type)
                $subtitle = ucfirst($favourite->object_model ?? 'Service');

                // Get category
                $category = '';
                if ($favourite->object_model === 'tour' && isset($service->category_id)) {
                    $tourCategory = \Modules\Tour\Models\TourCategory::find($service->category_id);
                    $category = $tourCategory ? $tourCategory->name : '';
                }

                // Get image
                $image = asset('images/placeholder.jpg');
                if (isset($service->image_id)) {
                    $image = get_file_url($service->image_id, 'medium') ?? $image;
                } elseif (isset($service->banner_image_id)) {
                    $image = get_file_url($service->banner_image_id, 'medium') ?? $image;
                }

                // Get price
                $price = $service->sale_price ?? $service->price ?? 0;
                $priceText = number_format($price, 2).get_current_currency_svg();

                // Get location
                $location = '';
                if ($service->location) {
                    $location = $service->location->name ?? '';
                }

                return [
                    'id' => $favourite->id,
                    'title' => $title,
                    'subtitle' => $subtitle,
                    'category' => $category,
                    'image' => $image,
                    'price' => $priceText,
                    'location' => $location,
                    'added_date' => $favourite->created_at->format('d/m/Y H:i'),
                    'service_url' => method_exists($service, 'getDetailUrl') ? $service->getDetailUrl() : '#',
                ];
            })->filter()->values();

            return response()->json([
                'success' => true,
                'items' => $items,
                'count' => $items->count(),
            ]);
        }

        $data = [
            'page_title' => __('User Favourites'),
            'favourites' => $favourites,
        ];

        return view('admin.favourite.show', $data);
    }

    /**
     * Delete a favourite.
     */
    public function destroy(UserWishList $favourite)
    {
        $favourite->delete();

        return redirect()->back()->with('success', __('Favourite deleted successfully!'));
    }
}
