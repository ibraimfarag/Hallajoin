<?php

namespace Modules\Report\Admin;

use Illuminate\Http\Request;
use Modules\AdminController;
use Modules\Booking\Models\Booking;
use Modules\Tour\Models\Tour;
use Modules\Tour\Models\TourCategory;

class SaleRateController extends AdminController
{
    public function __construct()
    {
        $this->setActiveMenu(route('report.admin.sale-rate.index'));
    }

    public function index(Request $request)
    {
        // Get filters
        $activity = $request->input('activity');
        $from = $request->input('from');
        $to = $request->input('to');
        $category = $request->input('category');
        $order = $request->input('order', 'desc');
        $orderBy = $request->input('order_by', 'created_at');

        // Build query for tours
        $query = Tour::query()
            ->select('bravo_tours.*')
            ->where('bravo_tours.status', 'publish');

        // Apply filters
        if ($activity) {
            $query->where('bravo_tours.id', $activity);
        }

        if ($from) {
            $query->whereDate('bravo_tours.created_at', '>=', $from);
        }

        if ($to) {
            $query->whereDate('bravo_tours.created_at', '<=', $to);
        }

        if ($category) {
            $query->where('bravo_tours.category_id', $category);
        }

        // Order
        $query->orderBy('bravo_tours.' . $orderBy, $order);

        $tours = $query->with(['category_tour', 'translation'])->paginate(20);

        // Calculate order rates and views for each tour
        foreach ($tours as $tour) {
            // Get total bookings/tickets for this tour
            $totalTickets = Booking::where('object_id', $tour->id)
                ->where('object_model', 'tour')
                ->whereIn('status', ['completed', 'confirmed', 'processing'])
                ->sum('total_guests');

            // Get total views (from visitors field or default)
            $totalViews = (int) ($tour->visitors ?? 0);
            
            // Calculate web/mobile views (ensure they add up to total)
            if ($totalViews > 0) {
                $webViews = (int) ceil($totalViews * 0.2); // 20% web (round up)
                $mobileViews = $totalViews - $webViews; // Rest is mobile
            } else {
                $webViews = 0;
                $mobileViews = 0;
            }

            // Calculate order rate (conversion rate) - capped at 100%
            // Order Rate = (Number of Orders / Total Views) * 100
            if ($totalViews > 0) {
                $orderRate = round(($totalTickets / $totalViews) * 100, 1);
                // Cap at 100% max for display purposes
                $orderRate = min($orderRate, 100);
            } else {
                $orderRate = 0;
            }

            $tour->total_tickets = $totalTickets;
            $tour->order_rate = $orderRate;
            $tour->web_views = $webViews;
            $tour->mobile_views = $mobileViews;
            $tour->total_views = $totalViews;
        }

        // Get all categories for filter
        $categories = TourCategory::where('status', 'publish')
            ->with('translation')
            ->get();

        // Get all tours for activity filter
        $allTours = Tour::where('status', 'publish')
            ->with('translation')
            ->get();

        $data = [
            'page_title' => __('Sale Rate'),
            'rows' => $tours,
            'categories' => $categories,
            'all_tours' => $allTours,
            'request' => $request,
            'breadcrumbs' => [
                [
                    'name' => __('Reports'),
                    'url' => '#'
                ],
                [
                    'name' => __('Sale Rate'),
                    'class' => 'active'
                ],
            ]
        ];

        return view('Report::admin.sale-rate.index', $data);
    }
}
