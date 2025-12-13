@extends ('admin.layouts.app')

@push('css')
    <style>
        :root {
            /* Dark Mode Colors (Default) */
            --cart-bg-primary: #0f1c2e;
            --cart-bg-secondary: #1a2942;
            --cart-bg-hover: rgba(99, 179, 237, 0.1);
            --cart-text-muted: #8b92a7;
        }

        /* Light Mode Override */
        [data-theme="light"] {
            --cart-bg-primary: #f7fafc;
            --cart-bg-secondary: #ffffff;
            --cart-bg-hover: #edf2f7;
            --cart-text-muted: #718096;
        }

        body.dark-mode .container-fluid {
            background: #0f1c2e;
        }

        body.dark-mode .card,
        body.dark-mode .panel {
            background: transparent;
            box-shadow: none;
        }

        .card {
            border: 0px solid rgba(0, 0, 0, .125);
        }

        body.dark-mode .form-control {
            height: 50px;
            color: #c5c5c5 !important;
            background: #122438;
            border: 1.9px solid #1b3d63;
            border-radius: 8px;
            padding: 0 20px;
        }

        body.dark-mode .input-group-text i {
            color: #fff !important;
        }

        body.dark-mode .fas {
            color: #fff !important;
        }

        /* Light Mode Styles */
        body:not(.dark-mode) .filter-container {
            background: #f8fafc !important;
            border: 1px solid #e2e8f0;
        }

        body:not(.dark-mode) .form-control {
            height: 50px;
            color: #374151 !important;
            background: #fff;
            border: 1.9px solid #d1d5db;
            border-radius: 8px;
            padding: 0 20px;
        }

        body:not(.dark-mode) input[type="date"]::-webkit-calendar-picker-indicator {
            filter: invert(0);
        }

        body.dark-mode input[type="date"]::-webkit-calendar-picker-indicator {
            filter: invert(1);
        }

        body:not(.dark-mode) .input-group-text i {
            color: #6b7280 !important;
        }

        body:not(.dark-mode) .fas {
            color: #6b7280 !important;
        }

        body:not(.dark-mode) .card {
            background: #fff;
            border: 1px solid #e5e7eb;
        }

        body:not(.dark-mode) .table-responsive {
            background: #f8fafc !important;
        }

        body:not(.dark-mode) .table-dark {
            background: #fff !important;
            color: #374151 !important;
        }

        body:not(.dark-mode) .table-dark th,
        body:not(.dark-mode) .table-dark td {
            background: #fff !important;
            color: #374151 !important;
            border-color: #e5e7eb !important;
        }

        body:not(.dark-mode) .table-dark tbody tr:hover {
            background: #f9fafb !important;
        }

        body:not(.dark-mode) h1 {
            color: #374151;
        }

        body.dark-mode .card-body {
            padding: 0 !important;
        }

        body.dark-mode .table thead {
            background: var(--cart-bg-hover);
        }

        body.dark-mode .table thead th {
            font-size: 19px !important;
            font-weight: 600;
            color: var(--cart-text-muted);
            letter-spacing: 0.5px;
            text-transform: none;
        }

        .table td,
        body.dark-mode .table th {
            padding: 22px 12px !important;
        }

        .table thead tr th {
            padding: 18px 18px !important;
        }

        .activity-link {
            color: #60a5fa;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 19px;
        }

        .activity-link:hover {
            color: #93c5fd;
        }

        .activity-link i {
            font-size: 12px;
        }

        .order-rate-cell {
            line-height: 1.5;
        }

        .order-rate-cell .rate {
            font-weight: 600;
            font-size: 18px;
        }

        .order-rate-cell .tickets {
            font-size: 14px;
            opacity: 0.7;
        }

        .views-cell {
            line-height: 1.6;
        }

        .views-cell .view-item {
            font-size: 15px;
        }

        .views-cell .trend-down {
            color: #f472b6;
            margin-left: 5px;
        }

        .orders-title {
            font-size: 24px;
            font-weight: 700;
            margin: 2px -1px 45px;
            padding-top: 26px;
        }

 body.dark-mode .page-item.active .page-link {
            z-index: 3;
            color: #fff;
            background-color: #007bff4a !important;
        }



        body.dark-mode .page-link {
            background-color: transparent !important;
            border: 1px solid #132438 !important;
            color: #a9a9a9 !important;

        }
    </style>
@endpush

@section('content')
    <div class="container-fluid">
        <h1 class="orders-title">{{ __('Activities Sales Rates') }}</h1>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">

                        {{-- Filter Section --}}
                        <div class="p-3 mb-4 filter-container" style="background:#132438; border-radius:18px;">
                            <form method="GET" action="{{ route('report.admin.sale-rate.index') }}" class="mb-0">
                                <div class="row g-2 align-items-center mb-2">
                                    <div class="col-md-2">
                                        <select name="activity" class="form-control"
                                            style="border-radius:20px; font-size:15px;">
                                            <option value="">{{ __('Activity') }}</option>
                                            @foreach ($all_tours as $tour)
                                                <option value="{{ $tour->id }}"
                                                    {{ request('activity') == $tour->id ? 'selected' : '' }}>
                                                    {{ $tour->title }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="input-group">
                                            <input type="date" name="from" class="form-control"
                                                placeholder="{{ __('From') }}" value="{{ request('from') }}"
                                                style="border-radius:20px; font-size:15px;">
                                            <span class="input-group-text"
                                                style="background:transparent; border:none; color:#7c8ba1; font-size:18px;">
                                                <i class="fas fa-calendar-alt"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="input-group">
                                            <input type="date" name="to" class="form-control"
                                                placeholder="{{ __('To') }}" value="{{ request('to') }}"
                                                style="border-radius:20px; font-size:15px;">
                                            <span class="input-group-text"
                                                style="background:transparent; border:none; color:#7c8ba1; font-size:18px;">
                                                <i class="fas fa-calendar-alt"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <select name="category" class="form-control"
                                            style="border-radius:20px; font-size:15px;">
                                            <option value="">{{ __('Category') }}</option>
                                            @foreach ($categories as $cat)
                                                <option value="{{ $cat->id }}"
                                                    {{ request('category') == $cat->id ? 'selected' : '' }}>
                                                    {{ $cat->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <select name="order" class="form-control"
                                            style="border-radius:20px; font-size:15px;">
                                            <option value="desc"
                                                {{ request('order', 'desc') == 'desc' ? 'selected' : '' }}>
                                                {{ __('Order') }} ↓
                                            </option>
                                            <option value="asc" {{ request('order') == 'asc' ? 'selected' : '' }}>
                                                {{ __('Order') }} ↑
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                <div class="d-flex mt-3 mb-2 filter-btn-bar">
                                    <button type="submit" class="btn"
                                        style="background:#7c3aed; color:#fff; font-weight:600; border-radius:8px; padding:8px 24px; min-width:90px; font-size: 14px;">
                                        {{ __('FILTER') }}
                                    </button>
                                    <a href="{{ route('report.admin.sale-rate.index') }}" class="btn"
                                        style="background:#374151; color:#b5b5b5; font-weight:600; border-radius:8px; padding:8px 20px; min-width:90px; font-size: 14px; margin-left:12px;">
                                        {{ __('RESET') }}
                                    </a>
                                </div>
                            </form>
                        </div>

                        {{-- Sales Table --}}
                        <div class="table-responsive" style="padding: 2px; background: #132438; border-radius: 20px;">
                            <table class="table table-dark table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th>{{ __('Activity Name') }}</th>
                                        <th>{{ __('Category Name') }}</th>
                                        <th>{{ __('Date') }} <i class="fa fa-arrow-down"></i></th>
                                        <th>{{ __('Order Rate') }}</th>
                                        <th>{{ __('Total Views') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($rows as $row)
                                        <tr>
                                            <td>
                                                <a href="{{ $row->getDetailUrl() }}" target="_blank" class="activity-link">
                                                    {{ $row->title }}
                                                    <i class="fa fa-external-link-alt"></i>
                                                </a>
                                            </td>
                                            <td><span style="font-size: 18px">{{ $row->category_tour->name ?? '-' }}</span>
                                            </td>
                                            <td><span
                                                    style="font-size: 18px">{{ $row->created_at ? $row->created_at->format('d/M/Y') : '-' }}</span>
                                            </td>
                                            <td class="order-rate-cell">
                                                <div class="rate">{{ $row->order_rate }} %</div>
                                                <div class="tickets">Tickets: {{ $row->total_tickets }}</div>
                                            </td>
                                            <td class="views-cell">
                                                <div class="view-item">Web View: {{ number_format($row->web_views) }}</div>
                                                <div class="view-item">
                                                    Mobile View: {{ number_format($row->mobile_views) }}
                                                    <i class="fa fa-arrow-down trend-down"></i>
                                                </div>
                                                <div class="view-item">Total View: {{ number_format($row->total_views) }}
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted">
                                                {{ __('No activities found') }}</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        {{-- Pagination --}}
                        @if ($rows->hasPages())
                            <div class="d-flex justify-content-center mt-3">
                                {{ $rows->appends(request()->query())->links() }}
                            </div>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Initialize select2 if available
            if ($.fn.select2) {
                $('.filter-container select').select2({
                    allowClear: true,
                    placeholder: function() {
                        return $(this).find('option:first').text();
                    }
                });
            }
        });
    </script>
@endpush
