<?php

namespace Modules\Report;

use Modules\User\Models\Wallet\DepositPayment;

class ModuleProvider extends \Modules\ModuleServiceProvider
{
    public function register()
    {

        $this->app->register(RouteServiceProvider::class);
    }

    public static function getAdminMenu()
    {
        $count = 0;
        $pending_purchase = DepositPayment::countPending();
        $count += $pending_purchase;

        return [
            'sales' => [
                'position' => 20,
                'url' => route('report.admin.booking'),
                'title' => __('Sales'),
                'icon' => 'icon ion-md-cart',
                'permission' => 'report_view',
                'children' => [
                    'orders' => [
                        'url' => route('report.admin.booking'),
                        'title' => __('Orders'),
                        'icon' => 'fa fa-list',
                        'permission' => 'report_view',
                    ],
                    'enquiry' => [
                        'url' => route('report.admin.enquiry.index'),
                        'title' => __('Inquiries'),
                        'icon' => 'fa fa-question-circle',
                        'permission' => 'report_view',
                    ],
                ],
            ],
            'reports' => [
                'position' => 22,
                'url' => '#',
                'title' => __('Reports'),
                'icon' => 'icon ion-ios-pie',
                'parent' => 'marketing',
                'permission' => 'report_view',
                'children' => [
                    'sale_rate' => [
                        'url' => route('report.admin.sale-rate.index'),
                        'title' => __('Sale Rate'),
                        'icon' => 'fa fa-chart-line',
                        'permission' => 'report_view',
                    ],
                    'search_history' => [
                        'url' => route('report.admin.search-history.index'),
                        'title' => __('Search History'),
                        // 'icon' => 'fa fa-search',
                        'permission' => 'report_view',
                    ],
                    'sales_pulse' => [
                        'url' => route('report.admin.sales-pulse.index'),
                        'title' => __('Sales Pulse'),
                        'permission' => 'report_view',
                    ],
                ],
            ],

        ];
    }
}
