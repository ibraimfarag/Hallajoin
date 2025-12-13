<?php

namespace Modules\Report\Admin;

use Illuminate\Http\Request;
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

            // Calculate days for averages
            $range1Days = max(1, \Carbon\Carbon::parse($range1From)->diffInDays(\Carbon\Carbon::parse($range1To)) + 1);
            $range2Days = max(1, \Carbon\Carbon::parse($range2From)->diffInDays(\Carbon\Carbon::parse($range2To)) + 1);

            foreach ($items as $item) {
                // Get Range 1 sales count
                $range1Query = Booking::where('object_id', $item->id)
                    ->where('object_model', $type)
                    ->whereDate('created_at', '>=', $range1From)
                    ->whereDate('created_at', '<=', $range1To);

                if ($orderStatus !== 'all') {
                    $range1Query->where('status', $orderStatus);
                }
                $range1Sales = $range1Query->count();

                // Get Range 2 sales count
                $range2Query = Booking::where('object_id', $item->id)
                    ->where('object_model', $type)
                    ->whereDate('created_at', '>=', $range2From)
                    ->whereDate('created_at', '<=', $range2To);

                if ($orderStatus !== 'all') {
                    $range2Query->where('status', $orderStatus);
                }
                $range2Sales = $range2Query->count();

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

        // Filter out items with no sales in both ranges (optional - keep all for now)
        // $services = array_filter($services, fn($s) => $s['range1_sales'] > 0 || $s['range2_sales'] > 0);

        $data = [
            'page_title' => __('Sales Pulse'),
            'services' => $services,
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
