@extends ('admin.layouts.app')

@push('css')
    <style>
        :root {
            --cart-bg-primary: #0f1c2e;
            --cart-bg-secondary: #1a2942;
            --cart-bg-hover: rgba(99, 179, 237, 0.1);
            --cart-text-muted: #8b92a7;
        }

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

        body.dark-mode .card-body {
            padding: 0 !important;
        }

        /* Light Mode Styles */
        body:not(.dark-mode) .filter-container {
            background: #f8fafc !important;
            border: 1px solid #e2e8f0;
        }

        body:not(.dark-mode) .card {
            background: #fff;
            border: 1px solid #e5e7eb;
        }

        body:not(.dark-mode) .sales-table-container {
            background: #fff !important;
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

        body:not(.dark-mode) input[type="date"]::-webkit-calendar-picker-indicator {
            filter: invert(0);
        }

        body.dark-mode input[type="date"]::-webkit-calendar-picker-indicator {
            filter: invert(1);
        }

        /* Filter Container - matching reference */
        .filter-container {
            background: #132438;
            border-radius: 12px;
            padding: 20px 25px;
            margin-bottom: 30px;
            display: flex;
            flex-wrap: wrap;
            align-items: flex-end;
            gap: 30px;
        }

        .filter-item {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .filter-item label {
            font-size: 12px;
            color: #8b92a7;
            margin-bottom: 0;
            font-weight: 400;
        }

        .range-group {
            display: flex;
            align-items: center;
            gap: 10px;
            background: transparent;
            border-radius: 8px;
            padding: 10px 15px;
            border: 1px solid #1b3d63;
        }

        body:not(.dark-mode) .range-group {
            background: #f3f4f6;
            border-color: #d1d5db;
        }

        .range-group input[type="date"] {
            height: 28px !important;
            background: transparent !important;
            border: none !important;
            padding: 0 !important;
            font-size: 14px;
            width: 115px;
            color: #c5c5c5;
        }

        body:not(.dark-mode) .range-group input[type="date"] {
            color: #374151;
        }

        .range-arrow {
            color: #8b92a7;
            font-size: 14px;
        }

        .range-group .calendar-icon {
            color: #8b92a7;
            cursor: pointer;
            font-size: 14px;
        }

        /* Dropdown styling - matching reference */
        .custom-select {
            height: 48px;
            background: transparent;
            border: 1px solid #1b3d63;
            border-radius: 8px;
            color: #c5c5c5;
            padding: 0 35px 0 15px;
            font-size: 14px;
            min-width: 200px;
            appearance: none;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%238b92a7' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 10px center;
            background-repeat: no-repeat;
            background-size: 20px;
        }

        body:not(.dark-mode) .custom-select {
            background-color: #fff;
            border-color: #d1d5db;
            color: #374151;
        }

        .btn-filter {
            background: #7c3aed;
            color: #fff;
            font-weight: 600;
            border-radius: 8px;
            padding: 12px 28px;
            font-size: 14px;
            border: none;
            height: 48px;
        }

        .btn-filter:hover {
            background: #6d28d9;
            color: #fff;
        }

        /* Sales Pulse Table - matching reference exactly */
        .sales-table-container {
            background: #132438;
            border-radius: 12px;
            overflow: hidden;
        }

        .sales-pulse-title {
            font-size: 18px;
            font-weight: 600;
            padding: 20px 25px;
            margin: 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        body:not(.dark-mode) .sales-pulse-title {
            border-bottom-color: #e5e7eb;
        }

        .table {
            margin-bottom: 0;
        }

        .table thead {
            background: transparent;
        }

        .table thead th {
            font-size: 13px !important;
            font-weight: 500;
            color: #8b92a7 !important;
            letter-spacing: 0;
            text-transform: none;
            padding: 15px 25px !important;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05) !important;
            vertical-align: bottom;
        }

        body:not(.dark-mode) .table thead th {
            border-bottom-color: #e5e7eb !important;
        }

        .table tbody td {
            padding: 15px 25px !important;
            border-bottom: 1px solid rgba(255, 255, 255, 0.03) !important;
            vertical-align: middle;
        }

        body:not(.dark-mode) .table tbody td {
            border-bottom-color: #f3f4f6 !important;
        }

        .table tbody tr:hover {
            background: rgba(255, 255, 255, 0.02);
        }

        .activity-cell {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .activity-image {
            width: 55px;
            height: 55px;
            border-radius: 10px;
            object-fit: cover;
            flex-shrink: 0;
        }

        .activity-name {
            color: #60a5fa;
            text-decoration: none;
            font-size: 15px;
            font-weight: 500;
            line-height: 1.4;
        }

        .activity-name:hover {
            color: #93c5fd;
            text-decoration: underline;
        }

        .sales-cell {
            text-align: right;
            white-space: nowrap;
        }

        .sales-value {
            font-size: 15px;
            font-weight: 600;
            display: inline;
        }

        .sales-avg {
            font-size: 14px;
            color: #8b92a7;
            font-weight: 400;
            display: inline;
            margin-left: 3px;
        }

        .trend-cell {
            width: 40px;
            text-align: center;
        }

        .trend-up {
            color: #4ade80;
            font-size: 18px;
        }

        .trend-down {
            color: #f87171;
            font-size: 18px;
        }

        .trend-same {
            color: #8b92a7;
            font-size: 18px;
        }

        @media (max-width: 1200px) {
            .filter-container {
                flex-direction: column;
                align-items: stretch;
            }

            .filter-item {
                width: 100%;
            }

            .range-group {
                justify-content: space-between;
            }

            .custom-select {
                width: 100%;
            }
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">

                        {{-- Filter Section --}}
                        <form method="GET" action="{{ route('report.admin.sales-pulse.index') }}"
                            class="filter-container">
                            {{-- Range 1 --}}
                            <div class="filter-item">
                                <label>{{ __('Range 1') }}</label>
                                <div class="range-group">
                                    <input type="date" name="range1_from" value="{{ $filters['range1_from'] }}">
                                    <span class="range-arrow">→</span>
                                    <input type="date" name="range1_to" value="{{ $filters['range1_to'] }}">
                                    <i class="fas fa-calendar-alt calendar-icon"></i>
                                </div>
                            </div>

                            {{-- Range 2 --}}
                            <div class="filter-item">
                                <label>{{ __('Range 2') }}</label>
                                <div class="range-group">
                                    <input type="date" name="range2_from" value="{{ $filters['range2_from'] }}">
                                    <span class="range-arrow">→</span>
                                    <input type="date" name="range2_to" value="{{ $filters['range2_to'] }}">
                                    <i class="fas fa-calendar-alt calendar-icon"></i>
                                </div>
                            </div>

                            {{-- Sort --}}
                            <div class="filter-item">
                                <label>{{ __('Sort') }}</label>
                                <select name="sort" class="custom-select">
                                    <option value="range1_desc" {{ $filters['sort'] == 'range1_desc' ? 'selected' : '' }}>
                                        {{ __('Range 1 Desc') }}</option>
                                    <option value="range1_asc" {{ $filters['sort'] == 'range1_asc' ? 'selected' : '' }}>
                                        {{ __('Range 1 Asc') }}</option>
                                    <option value="range2_desc" {{ $filters['sort'] == 'range2_desc' ? 'selected' : '' }}>
                                        {{ __('Range 2 Desc') }}</option>
                                    <option value="range2_asc" {{ $filters['sort'] == 'range2_asc' ? 'selected' : '' }}>
                                        {{ __('Range 2 Asc') }}</option>
                                </select>
                            </div>

                            {{-- Activity Type --}}
                            <div class="filter-item">
                                <label>{{ __('Activity') }}</label>
                                <input type="text" name="activity_search" class="custom-select"
                                    placeholder="{{ __('Search activity...') }}"
                                    value="{{ $filters['activity_search'] ?? '' }}"
                                    style="background-image: none; padding-right: 15px;">
                            </div>

                            {{-- Order Status --}}
                            <div class="filter-item">
                                <label>{{ __('Orders') }}</label>
                                <select name="order_status" class="custom-select">
                                    <option value="all" {{ $filters['order_status'] == 'all' ? 'selected' : '' }}>
                                        {{ __('All') }}</option>
                                    <option value="completed"
                                        {{ $filters['order_status'] == 'completed' ? 'selected' : '' }}>
                                        {{ __('Completed') }}</option>
                                    <option value="processing"
                                        {{ $filters['order_status'] == 'processing' ? 'selected' : '' }}>
                                        {{ __('Processing') }}</option>
                                    <option value="pending" {{ $filters['order_status'] == 'pending' ? 'selected' : '' }}>
                                        {{ __('Pending') }}</option>
                                    <option value="cancelled"
                                        {{ $filters['order_status'] == 'cancelled' ? 'selected' : '' }}>
                                        {{ __('Cancelled') }}</option>
                                </select>
                            </div>

                            {{-- Filter Button --}}
                            <div class="filter-item">
                                <label>&nbsp;</label>
                                <button type="submit" class="btn btn-filter">{{ __('FILTER') }}</button>
                            </div>
                        </form>

                        {{-- Sales Pulse Table --}}
                        <div class="sales-table-container">
                            <h3 class="sales-pulse-title">{{ __('Sales Pulse') }}</h3>
                            <table class="table table-dark table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th>{{ __('Activity') }}</th>
                                        <th class="text-end" style="width: 130px;">
                                            {{ __('Range 1') }}<br>{{ __('Sales') }}</th>
                                        <th class="text-end" style="width: 130px;">
                                            {{ __('Range 2') }}<br>{{ __('Sales') }}</th>
                                        <th class="trend-cell"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($services as $service)
                                        <tr>
                                            <td>
                                                <div class="activity-cell">
                                                    <img src="{{ $service['image'] ?: asset('images/placeholder.png') }}"
                                                        alt="{{ $service['title'] }}" class="activity-image">
                                                    <a href="{{ route($service['type'] . '.admin.edit', $service['id']) }}"
                                                        class="activity-name">
                                                        {{ $service['title'] }}
                                                    </a>
                                                </div>
                                            </td>
                                            <td class="sales-cell">
                                                <span class="sales-value">{{ $service['range1_sales'] }}</span>
                                                <span class="sales-avg">({{ $service['range1_avg'] }} avg)</span>
                                            </td>
                                            <td class="sales-cell">
                                                <span class="sales-value">{{ $service['range2_sales'] }}</span>
                                                <span class="sales-avg">({{ $service['range2_avg'] }} avg)</span>
                                            </td>
                                            <td class="trend-cell">
                                                @if ($service['trend'] == 'up')
                                                    <i class="fa fa-arrow-up trend-up"></i>
                                                @elseif($service['trend'] == 'down')
                                                    <i class="fa fa-arrow-down trend-down"></i>
                                                @else
                                                    <i class="fa fa-minus trend-same"></i>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted py-5">
                                                {{ __('No activities found') }}
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
