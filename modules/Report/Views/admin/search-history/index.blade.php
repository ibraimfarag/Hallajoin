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

        .orders-title {
            font-size: 24px;
            font-weight: 700;
            margin: 2px -1px 45px;
            padding-top: 26px;
        }

        /* Top Searches Cards */
        .top-searches-container {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }

        .top-search-card {
            background: #132438;
            border-radius: 12px;
            padding: 20px;
        }

        body:not(.dark-mode) .top-search-card {
            background: #fff;
            border: 1px solid #e5e7eb;
        }

        .top-search-card h3 {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 20px;
            color: inherit;
        }

        .search-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        body:not(.dark-mode) .search-item {
            border-bottom-color: #e5e7eb;
        }

        .search-item:last-child {
            border-bottom: none;
        }

        .search-item .keyword {
            font-size: 16px;
            color: inherit;
        }

        .search-item .count {
            font-size: 16px;
            font-weight: 600;
            color: inherit;
        }

        /* Keyword Link */
        .keyword-link {
            color: #60a5fa;
            text-decoration: none;
            font-size: 18px;
        }

        .keyword-link:hover {
            color: #93c5fd;
            text-decoration: underline;
        }

        /* Platform Icons */
        .platform-icon {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 16px;
        }

        .platform-icon img {
            width: 20px;
            height: 20px;
        }

        .platform-icon.apple {
            color: #a0aec0;
        }

        .platform-icon.android {
            color: #4ade80;
        }

        /* Export Button */
        .btn-export {
            background: #374151;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-export:hover {
            background: #4b5563;
            color: #fff;
        }

        /* Section Title with Export */
        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding: 20px;
            padding-bottom: 0;
        }

        .section-header h3 {
            font-size: 18px;
            font-weight: 600;
            margin: 0;
        }

        /* Checkbox Styles */
        .filter-checkbox {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .filter-checkbox input[type="checkbox"] {
            width: 18px;
            height: 18px;
            accent-color: #7c3aed;
        }

        .filter-checkbox label {
            font-size: 15px;
            color: inherit;
            margin: 0;
            white-space: nowrap;
        }

        @media (max-width: 992px) {
            .top-searches-container {
                grid-template-columns: 1fr;
            }
        }


        input[type="checkbox"], input[type="radio"]

 {
   
    background: transparent !important;

 }
    </style>
@endpush

@section('content')
    <div class="container-fluid"style="padding: 24px;">
        <div class="d-flex justify-content-between mb20">
            <h1 class="title-bar">{{  __('Search History') }}</h1>
           
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">

                        {{-- Top Searches Cards --}}
                        <div class="top-searches-container">
                            {{-- 24 Hours Search --}}
                            <div class="top-search-card">
                                <h3>{{ __('24 Hours Search') }}</h3>
                                @forelse($top24Hours as $item)
                                    <div class="search-item">
                                        <span class="keyword">{{ $item->keyword }}</span>
                                        <span class="count">{{ $item->count }}</span>
                                    </div>
                                @empty
                                    <div class="text-muted text-center py-3">{{ __('No data') }}</div>
                                @endforelse
                            </div>

                            {{-- 7 Days Search --}}
                            <div class="top-search-card">
                                <h3>{{ __('7 Days Search') }}</h3>
                                @forelse($top7Days as $item)
                                    <div class="search-item">
                                        <span class="keyword">{{ $item->keyword }}</span>
                                        <span class="count">{{ $item->count }}</span>
                                    </div>
                                @empty
                                    <div class="text-muted text-center py-3">{{ __('No data') }}</div>
                                @endforelse
                            </div>

                            {{-- 30 Days Search --}}
                            <div class="top-search-card">
                                <h3>{{ __('30 Days Search') }}</h3>
                                @forelse($top30Days as $item)
                                    <div class="search-item">
                                        <span class="keyword">{{ $item->keyword }}</span>
                                        <span class="count">{{ $item->count }}</span>
                                    </div>
                                @empty
                                    <div class="text-muted text-center py-3">{{ __('No data') }}</div>
                                @endforelse
                            </div>
                        </div>

                        {{-- Filter Section --}}
                        <div class="p-3 mb-4 filter-container" style="background:#132438; border-radius:18px;">
                            <form method="GET" action="{{ route('report.admin.search-history.index') }}" class="mb-0">
                                <div class="row g-2 align-items-center mb-2">
                                    <div class="col-md-2">
                                        <input type="text" name="search" class="form-control"
                                            placeholder="{{ __('Search') }}" value="{{ request('search') }}"
                                            style="border-radius:20px; font-size:15px;">
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
                                        <div class="filter-checkbox">
                                            <input type="checkbox" name="operators_only" id="operators_only" value="1"
                                                {{ request('operators_only') ? 'checked' : '' }}>
                                            <label for="operators_only">{{ __('Operators Only') }}</label>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="filter-checkbox">
                                            <input type="checkbox" name="from_b2b" id="from_b2b" value="1"
                                                {{ request('from_b2b') ? 'checked' : '' }}>
                                            <label for="from_b2b">{{ __('From B2B') }}</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex mt-3 mb-2 filter-btn-bar">
                                    <button type="submit" class="btn"
                                        style="background:#7c3aed; color:#fff; font-weight:600; border-radius:8px; padding:8px 24px; min-width:90px; font-size: 14px;">
                                        {{ __('FILTER') }}
                                    </button>
                                    <a href="{{ route('report.admin.search-history.index') }}" class="btn"
                                        style="background:#374151; color:#b5b5b5; font-weight:600; border-radius:8px; padding:8px 20px; min-width:90px; font-size: 14px; margin-left:12px;">
                                        {{ __('RESET') }}
                                    </a>
                                </div>
                            </form>
                        </div>

                        {{-- Search History Table --}}
                        <div class="table-responsive" style="padding: 2px; background: #132438; border-radius: 20px;">
                            <div class="section-header">
                                <h3>{{ __('') }}</h3>
                                <a href="{{ route('report.admin.search-history.export', request()->query()) }}"
                                    class="btn-export">
                                    <i class="fa fa-download"></i>
                                    {{ __('EXPORT') }}
                                </a>
                            </div>
                            <table class="table table-dark table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th>{{ __('Created On') }} <i class="fa fa-arrow-down"></i></th>
                                        <th>{{ __('Keyword') }}</th>
                                        <th>{{ __('User') }}</th>
                                        <th>{{ __('From') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($rows as $row)
                                        <tr>
                                            <td><span
                                                    style="font-size: 18px">{{ \Carbon\Carbon::parse($row->created_at)->format('d/M/Y H:i') }}</span>
                                            </td>
                                            <td>
                                                <a href="#" class="keyword-link">{{ $row->keyword }}</a>
                                            </td>
                                            <td>
                                                @if ($row->user_id && $row->user)
                                                    <div style="display: flex; align-items: center; gap: 12px;">
                                                        <img src="{{ $row->user->getAvatarUrl() }}"
                                                            alt="{{ $row->user->name }}"
                                                            style="width: 45px; height: 45px; border-radius: 50%; object-fit: cover;">
                                                        <div>
                                                            <a href="{{ route('user.admin.detail', $row->user_id) }}"
                                                                style="color: #60a5fa; text-decoration: none; font-size: 16px; display: block;">
                                                                {{ $row->user->name ?? $row->user->email }}
                                                            </a>
                                                            @if ($row->user->phone)
                                                                <span
                                                                    style="font-size: 14px; color: #8b92a7;">{{ $row->user->phone }}</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @else
                                                    <span style="font-size: 18px">{{ $row->user_ip ?? '-' }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($row->platform) && strtolower($row->platform) == 'ios')
                                                    <span class="platform-icon apple">
                                                        <i class="fab fa-apple"></i> Apple
                                                    </span>
                                                @elseif(isset($row->platform) && strtolower($row->platform) == 'android')
                                                    <span class="platform-icon android">
                                                        <i class="fab fa-android"></i> Android
                                                    </span>
                                                @else
                                                    <span class="platform-icon">
                                                        <i class="fa fa-globe"></i> {{ $row->platform ?? 'Web' }}
                                                    </span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted">
                                                {{ __('No search history found') }}</td>
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
