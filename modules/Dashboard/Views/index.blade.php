@extends('admin.layouts.app')

@section('content')
    <div class="container-fluid dashboard-modern">

        <div class="welcome-header">
            <h1 class="welcome-title">{{__('Welcome to Admin Panel!')}}</h1>
        </div>

        <!-- Top Row Stats -->
        <div class="row dashboard-stats-row">
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stat-card">
                    <div class="stat-content">
                        <h3 class="stat-title">{{__('Total Users')}}</h3>
                        <div class="stat-value">{{ \App\User::count() }}</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stat-card">
                    <div class="stat-content">
                        <h3 class="stat-title">{{__('Today Registrations')}}</h3>
                        <div class="stat-value">{{ \App\User::whereDate('created_at', today())->count() }}</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stat-card">
                    <div class="stat-content">
                        <h3 class="stat-title">{{__('Total Services')}}</h3>
                        <div class="stat-value">{{ 
                                            \DB::table('bravo_tours')->where('status', 'publish')->count() +
        \DB::table('bravo_hotels')->where('status', 'publish')->count() +
        \DB::table('bravo_cars')->where('status', 'publish')->count() +
        \DB::table('bravo_spaces')->where('status', 'publish')->count() +
        \DB::table('bravo_boats')->where('status', 'publish')->count() +
        \DB::table('bravo_events')->where('status', 'publish')->count() +
        \DB::table('bravo_flight')->where('status', 'publish')->count()
                                        }}</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stat-card">
                    <div class="stat-content">
                        <h3 class="stat-title">{{__('Pending Orders')}}</h3>
                        <div class="stat-value">
                            {{ \Modules\Booking\Models\Booking::whereIn('status', ['draft', 'processing'])->count() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cart Stats Row -->
        <div class="row dashboard-stats-row">
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stat-card">
                    <div class="stat-content">
                        <h3 class="stat-title">{{__('Total Carts')}}</h3>
                        <div class="stat-value">{{ \App\Models\Cart::count() }}</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stat-card">
                    <div class="stat-content">
                        <h3 class="stat-title">{{__('Active Carts')}}</h3>
                        <div class="stat-value">{{ \App\Models\Cart::where('status', 'active')->count() }}</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stat-card">
                    <div class="stat-content">
                        <h3 class="stat-title">{{__('Abandoned Carts')}}</h3>
                        <div class="stat-value">{{ \App\Models\Cart::where('status', 'abandoned')->count() }}</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stat-card">
                    <div class="stat-content">
                        <h3 class="stat-title">{{__('Cart Total Value')}}</h3>
                        <div class="stat-value">
                            {!! format_money_with_svg(\App\Models\Cart::where('status', 'active')->sum('total_amount')) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sales Stats Row -->
        <div class="row dashboard-stats-row">
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stat-card">
                    <div class="stat-content">
                        <h3 class="stat-title">{{__('Yesterday Sales')}}</h3>
                        <div class="stat-amount">
                            {!! format_money_with_svg(\Modules\Booking\Models\Booking::whereDate('created_at', now()->subDay())->whereIn('status', ['processing', 'draft'])->sum('total')) !!}
                        </div>
                        <div class="stat-desc">{{__('from orders')}}</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stat-card">
                    <div class="stat-content">
                        <h3 class="stat-title">{{__('Today Sales')}}</h3>
                        <div class="stat-amount">
                            {!! format_money_with_svg(\Modules\Booking\Models\Booking::whereDate('created_at', today())->whereIn('status', ['processing', 'draft'])->sum('total')) !!}
                        </div>
                        <div class="stat-desc">{{__('from orders')}}</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stat-card">
                    <div class="stat-content">
                        <h3 class="stat-title">{{__('Month Sales')}}</h3>
                        <div class="stat-amount">
                            {!! format_money_with_svg(\Modules\Booking\Models\Booking::whereMonth('created_at', now()->month)->whereIn('status', ['processing', 'draft'])->sum('total')) !!}
                        </div>
                        <div class="stat-desc">{{__('from orders')}}</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stat-card">
                    <div class="stat-content">
                        <h3 class="stat-title">{{__('Last Month Sales')}}</h3>
                        <div class="stat-amount">
                            {!! format_money_with_svg(\Modules\Booking\Models\Booking::whereMonth('created_at', now()->subMonth()->month)->whereIn('status', ['processing', 'draft'])->sum('total')) !!}
                        </div>
                        <div class="stat-desc">{{__('from orders')}}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- B2B Sales Stats Row -->
        <div class="row dashboard-stats-row">
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stat-card">
                    <div class="stat-content">
                        <h3 class="stat-title">{{__('B2B Yesterday Sales')}}</h3>
                        <div class="stat-amount">{!! format_money_with_svg(0.00) !!}</div>
                        <div class="stat-desc">{{__('from orders')}}</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stat-card">
                    <div class="stat-content">
                        <h3 class="stat-title">{{__('B2B Today Sales')}}</h3>
                        <div class="stat-amount">{!! format_money_with_svg(0.00) !!}</div>
                        <div class="stat-desc">{{__('from orders')}}</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stat-card">
                    <div class="stat-content">
                        <h3 class="stat-title">{{__('B2B Month Sales')}}</h3>
                        <div class="stat-amount">{!! format_money_with_svg(0.00) !!}</div>
                        <div class="stat-desc">{{__('from orders')}}</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stat-card">
                    <div class="stat-content">
                        <h3 class="stat-title">{{__('B2B Last Month Sales')}}</h3>
                        <div class="stat-amount">{!! format_money_with_svg(0.00) !!}</div>
                        <div class="stat-desc">{{__('from orders')}}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection