<?php

namespace Modules\Report\Admin;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Modules\AdminController;
use Modules\Booking\Models\Booking;

class SalesPulseController extends AdminController
{
    public function __construct()
    {
        $this->setActiveMenu(route('report.admin.sales-pulse.index'));
    }

    public function index(Request $request)
    {
        // Get filters
        $range1From = $request->input('range1_from', now()->subDays(30)->format('Y-m-d'));
        $range1To = $request->input('range1_to', now()->format('Y-m-d'));
        $range2From = $request->input('range2_from', now()->subDays(60)->format('Y-m-d'));
        $range2To = $request->input('range2_to', now()->subDays(31)->format('Y-m-d'));
        $sort = $request->input('sort', 'range1_desc');
        $activitySearch = $request->input('activity_search', '');
        $orderStatus = $request->input('order_status', 'all');
        $perPage = 15; // Items per page
        $currentPage = $request->input('page', 1);

        // Calculate days for averages
        $range1Days = max(1, \Carbon\Carbon::parse($range1From)->diffInDays(\Carbon\Carbon::parse($range1To)) + 1);
        $range2Days = max(1, \Carbon\Carbon::parse($range2From)->diffInDays(\Carbon\Carbon::parse($range2To)) + 1);

        // Get all booking counts in ONE query using conditional aggregation
        $bookingStatsQuery = Booking::query()
            ->select([
                'object_id',
                'object_model',
                DB::raw("SUM(CASE WHEN DATE(created_at) >= '{$range1From}' AND DATE(created_at) <= '{$range1To}' THEN 1 ELSE 0 END) as range1_sales"),
                DB::raw("SUM(CASE WHEN DATE(created_at) >= '{$range2From}' AND DATE(created_at) <= '{$range2To}' THEN 1 ELSE 0 END) as range2_sales"),
            ])
            ->where(function ($q) use ($range1From, $range1To, $range2From, $range2To) {
                $q->whereBetween(DB::raw('DATE(created_at)'), [$range1From, $range1To])
                    ->orWhereBetween(DB::raw('DATE(created_at)'), [$range2From, $range2To]);
            });

        if ($orderStatus !== 'all') {
            $bookingStatsQuery->where('status', $orderStatus);
        }

        $bookingStats = $bookingStatsQuery
            ->groupBy('object_id', 'object_model')
            ->get()
            ->keyBy(fn ($item) => $item->object_model.'_'.$item->object_id);

        // Get all bookable services
        $services = [];
        $allTypes = get_bookable_services();

        foreach ($allTypes as $type => $class) {
            if (! class_exists($class)) {
                continue;
            }

            $model = new $class;
            $tableName = $model->getTable();

            // Get items
            $query = $class::query()
                ->select([
                    $tableName.'.id',
                    $tableName.'.title',
                    $tableName.'.slug',
                    $tableName.'.image_id',
                ])
                ->where('status', 'publish');

            // Apply activity search filter
            if (! empty($activitySearch)) {
                $query->where('title', 'like', '%'.$activitySearch.'%');
            }

            $items = $query->get();

            foreach ($items as $item) {
                $key = $type.'_'.$item->id;
                $stats = $bookingStats->get($key);

                $range1Sales = $stats ? (int) $stats->range1_sales : 0;
                $range2Sales = $stats ? (int) $stats->range2_sales : 0;

                $range1Avg = round($range1Sales / $range1Days, 1);
                $range2Avg = round($range2Sales / $range2Days, 1);

                $services[] = [
                    'id' => $item->id,
                    'type' => $type,
                    'title' => $item->title,
                    'slug' => $item->slug,
                    'image' => get_file_url($item->image_id, 'thumb'),
                    'range1_sales' => $range1Sales,
                    'range1_avg' => $range1Avg,
                    'range2_sales' => $range2Sales,
                    'range2_avg' => $range2Avg,
                    'trend' => $range1Sales > $range2Sales ? 'up' : ($range1Sales < $range2Sales ? 'down' : 'same'),
                ];
            }
        }

        // Sort results
        usort($services, function ($a, $b) use ($sort) {
            switch ($sort) {
                case 'range1_desc':
                    return $b['range1_sales'] - $a['range1_sales'];
                case 'range1_asc':
                    return $a['range1_sales'] - $b['range1_sales'];
                case 'range2_desc':
                    return $b['range2_sales'] - $a['range2_sales'];
                case 'range2_asc':
                    return $a['range2_sales'] - $b['range2_sales'];
                default:
                    return $b['range1_sales'] - $a['range1_sales'];
            }
        });

        // Manual pagination
        $total = count($services);
        $offset = ($currentPage - 1) * $perPage;
        $paginatedServices = array_slice($services, $offset, $perPage);

        $pagination = new LengthAwarePaginator(
            $paginatedServices,
            $total,
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        $data = [
            'page_title' => __('Sales Pulse'),
            'services' => $pagination,
            'activity_types' => $allTypes,
            'filters' => [
                'range1_from' => $range1From,
                'range1_to' => $range1To,
                'range2_from' => $range2From,
                'range2_to' => $range2To,
                'sort' => $sort,
                'activity_search' => $activitySearch,
                'order_status' => $orderStatus,
            ],
            'breadcrumbs' => [
                [
                    'name' => __('Reports'),
                    'url' => '#',
                ],
                [
                    'name' => __('Sales Pulse'),
                    'class' => 'active',
                ],
            ],
        ];

        return view('Report::admin.sales-pulse.index', $data);
    }
}
