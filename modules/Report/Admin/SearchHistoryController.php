<?php

namespace Modules\Report\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\AdminController;
use Modules\Report\Models\SearchHistory;

class SearchHistoryController extends AdminController
{
    public function __construct()
    {
        $this->setActiveMenu(route('report.admin.search-history.index'));
    }

    public function index(Request $request)
    {
        // Get filters
        $search = $request->input('search');
        $from = $request->input('from');
        $to = $request->input('to');
        $operatorsOnly = $request->has('operators_only');
        $fromB2B = $request->has('from_b2b');

        // Build query for search history with user relationship
        $query = SearchHistory::with('user')
            ->orderBy('created_at', 'desc');

        // Apply filters
        if ($search) {
            $query->where('keyword', 'LIKE', '%'.$search.'%');
        }

        if ($from) {
            $query->whereDate('created_at', '>=', $from);
        }

        if ($to) {
            $query->whereDate('created_at', '<=', $to);
        }

        if ($operatorsOnly) {
            $query->where('is_operator', 1);
        }

        if ($fromB2B) {
            $query->where('from_b2b', 1);
        }

        $rows = $query->paginate(20);

        // Get top searches for 24 hours
        $top24Hours = DB::table('core_search_history')
            ->select('keyword', DB::raw('COUNT(*) as count'))
            ->where('created_at', '>=', now()->subHours(24))
            ->groupBy('keyword')
            ->orderBy('count', 'desc')
            ->limit(8)
            ->get();

        // Get top searches for 7 days
        $top7Days = DB::table('core_search_history')
            ->select('keyword', DB::raw('COUNT(*) as count'))
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy('keyword')
            ->orderBy('count', 'desc')
            ->limit(8)
            ->get();

        // Get top searches for 30 days
        $top30Days = DB::table('core_search_history')
            ->select('keyword', DB::raw('COUNT(*) as count'))
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('keyword')
            ->orderBy('count', 'desc')
            ->limit(8)
            ->get();

        $data = [
            'page_title' => __('Search History'),
            'rows' => $rows,
            'top24Hours' => $top24Hours,
            'top7Days' => $top7Days,
            'top30Days' => $top30Days,
            'request' => $request,
            'breadcrumbs' => [
                [
                    'name' => __('Reports'),
                    'url' => '#',
                ],
                [
                    'name' => __('Search History'),
                    'class' => 'active',
                ],
            ],
        ];

        return view('Report::admin.search-history.index', $data);
    }

    public function export(Request $request)
    {
        // Export functionality
        $query = DB::table('core_search_history')
            ->orderBy('created_at', 'desc');

        if ($request->input('search')) {
            $query->where('keyword', 'LIKE', '%'.$request->input('search').'%');
        }

        if ($request->input('from')) {
            $query->whereDate('created_at', '>=', $request->input('from'));
        }

        if ($request->input('to')) {
            $query->whereDate('created_at', '<=', $request->input('to'));
        }

        $data = $query->get();

        $filename = 'search_history_'.date('Y-m-d_H-i-s').'.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ];

        $callback = function () use ($data) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Created On', 'Keyword', 'User IP', 'Platform']);

            foreach ($data as $row) {
                fputcsv($file, [
                    $row->created_at,
                    $row->keyword,
                    $row->user_ip ?? '',
                    $row->platform ?? '',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
