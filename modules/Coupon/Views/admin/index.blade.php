@extends('admin.layouts.app')
@section('content')

<style>
    :root,
    [data-theme="dark"],
    body.dark-mode {
        /* Dark Mode Colors (Default) */
        --cart-bg-primary: #0f1c2e;
        --cart-bg-secondary: #1a2942;
        --cart-bg-hover: rgba(99, 179, 237, 0.1);
        --cart-text-primary: #ffffff;
        --cart-text-secondary: #e2e8f0;
        --cart-text-muted: #8b92a7;
        --cart-border-color: rgba(255, 255, 255, 0.1);
        --cart-border-light: rgba(255, 255, 255, 0.05);
    }

    /* Light Mode Override */
    [data-theme="light"],
    body:not(.dark-mode) {
        --cart-bg-primary: #f7fafc;
        --cart-bg-secondary: #ffffff;
        --cart-bg-hover: #edf2f7;
        --cart-text-primary: #1a202c;
        --cart-text-secondary: #2d3748;
        --cart-text-muted: #718096;
        --cart-border-color: #e2e8f0;
        --cart-border-light: #cbd5e0;
    }


    .promotion-container {
        background: var(--cart-bg-primary);
        min-height: 100vh;
        padding: 24px;
        color: var(--cart-text-secondary);
        transition: background-color 0.3s ease;
    }


    .main-breadcrumb {
        display: none !important
    }

    .btn-mint-outline svg {
        stroke: rgb(5, 150, 105);
        transition: stroke 0.3s;
    }

    .btn-mint-outline:hover svg,
    .btn-mint-outline:focus svg {
        stroke: #fff;
    }

    .btn-mint-outline {
        background: transparent;
        border: 2px solid rgb(5, 150, 105);
        color: rgb(5, 150, 105);
        padding: 10px 20px;
        border-radius: 15px;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: 0.3s;
        text-decoration: none;
    }

    .btn-mint-outline:hover,
    .btn-mint-outline:focus {
        background: #059669;
        color: #fff;
        text-decoration: none;
    }


    .coupon-filters-ui {
        background: var(--cart-bg-secondary);
        color: var(--cart-text-secondary);
        border-radius: 16px;
        padding: 22px 24px 10px 24px;
        margin-bottom: 28px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.10);
        width: 100%;
        max-width: 100%;
        min-height: 25vh;
    }

    .coupon-filters-ui .row {
        flex-wrap: wrap;
        gap: 12px 0;
    }

    .coupon-filters-ui .form-control,
    .coupon-filters-ui .form-select {
        background: #232b43;
        color: #fff;
        border: 1px solid #2c3550;
        border-radius: 10px;
        font-size: 15px;
        height: 40px;
        box-shadow: none;
        padding-left: 14px;
        padding-right: 14px;
        margin-bottom: 0;
    }

    .coupon-filters-ui .form-control:focus {
        background: #232b43;
        color: var(--cart-text-primary);
        border-color: #6c63ff;
        box-shadow: 0 0 0 2px #6c63ff33;
    }

    .coupon-filters-ui .form-check {
        display: flex;
        align-items: center;
        margin-bottom: 0;
        min-width: 120px;
    }

    .coupon-filters-ui .form-check-input {
        background: transparent;
        border: 1px solid var(--cart-text-primary);
        border-radius: 0;
        width: 20px;
        height: 20px;
        /* margin-right: 8px; */
        /* margin-top: 0; */
        margin: 0 0 0 -28px;
    }

    .coupon-filters-ui .form-check-label {
        color: var(--cart-text-primary);
        font-size: 15px;
        margin-right: 18px;
        margin-bottom: 0;
    }

    .coupon-filters-ui .btn-primary {
        background: #6c63ff;
        border: none;
        border-radius: 15px;
        font-weight: 600;
        font-size: 14px;
        padding: 8px 28px;
        box-shadow: none;
        /* width: 100%; */
        letter-spacing: 1px;
        transition: background 0.2s;
    }

    .coupon-filters-ui .btn-primary:hover {
        background: #5548c8;
    }

    .coupon-filters-ui .btn-secondary {
        background: var(--cart-bg-secondary);
        color: var(--cart-text-primary);
        border: none;
        border-radius: 15px;
        font-size: 14px;
        padding: 8px 28px;
        box-shadow: none;
        /* width: 100%; */
        letter-spacing: 1px;
        transition: background 0.2s;
    }

    .coupon-filters-ui .btn-secondary:hover {
        background: #2c3550;
    }

    body:not(.dark-mode) input[type="date"]::-webkit-calendar-picker-indicator {
        filter: invert(0);

    }

    body.dark-mode input[type="date"]::-webkit-calendar-picker-indicator {
        filter: invert(1);
        /* font-size: 25px; */
    }

    body.dark-mode .form-control:focus {
        background: transparent;
        border-color: transparent;
        color: var(--dark-text-primary);
        box-shadow: 0 0 0 0.2rem rgba(59, 130, 246, 0.25);
    }


    body.dark-mode .form-control {
        height: 50px;
        color: #c5c5c5 !important;
        background: transparent;
        border: 1.9px solid #1b3d63;
        border-radius: 18px;
        padding: 0 25px;
    }

    body:not(.dark-mode) .form-control {
        height: 50px;
        color: #374151 !important;
        background: #fff;
        border: 1.9px solid #d1d5db;
        border-radius: 18px;
        padding: 0 25px;
    }


    input[type="checkbox"]:checked:before {
        color: var(--cart-text-primary);
        content: "\f00c";
        font: normal normal normal 13px / 1 FontAwesome;
        margin: 2px 2px 3px 1px;
    }

    .coupon-edit-btn {
        color: var(--cart-text-primary);

    }

    body.dark-mode .table-hover tbody tr:hover {
        transform: none;
    }

    .panel {
        width: 100% !important;
        background: var(--cart-bg-secondary) !important;

        overflow: hidden;
    }


    thead {
        background: var(--cart-bg-hover);
    }

    .panel-body {
        padding: 0px !important;
    }

    .table thead th {
        font-size: 19px !important;
        font-weight: 600;
        color: var(--cart-text-muted) !important;
        letter-spacing: 0.5px;
        text-transform: none !important;
    }

    .cart-table td {
        padding: 23px 0px -4px !important;
        border-bottom: 1px solid var(--cart-border-light) !important;
        color: var(--cart-text-secondary) !important;
        font-size: 19px !important;
    }

</style>



<div class="container-fluid promotion-container">
    <div class="d-flex justify-content-between mb20">
        <h1 class="title-bar">{{ __('Promotion Codes') }}</h1>
        <div class="title-actions">
            @if(empty($recovery))
                <a href="{{ route('coupon.admin.create') }}" class="btn btn-mint-outline">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24"
                        style="vertical-align:middle;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13l-1.35 2.7A1 1 0 007.6 17h8.8a1 1 0 00.95-.68L21 13M7 13V6h13" />
                    </svg>
                    <span style="vertical-align:middle;letter-spacing:1px;font-weight:600;text-transform:uppercase;">Add
                        New Promotion</span>
                </a>
            @endif
        </div>
    </div>

    @include('admin.message')
    <div class="row">

        <form method="get" action="" class="coupon-filters-ui mb-4">
            <div class="row align-items-end">
                <div class="col-md-2 mb-2">
                    <input type="text" name="s" class="form-control" placeholder="Search"
                        value="{{ request('s') }}">
                </div>
                <div class="col-md-2 mb-2">
                    <input type="date" name="from" class="form-control" placeholder="From"
                        value="{{ request('from') }}">
                </div>
                <div class="col-md-2 mb-2">
                    <input type="date" name="to" class="form-control" placeholder="To"
                        value="{{ request('to') }}">
                </div>
                <div class="col-md-2 mb-2">
                    <select name="active" class="form-control">
                        <option value="1"
                            {{ request('active') == '1' ? 'selected' : '' }}>
                            Active</option>
                        <option value="0"
                            {{ request('active') == '0' ? 'selected' : '' }}>
                            Inactive</option>
                    </select>
                </div>
                <div class="col-md-2 mb-2">
                    <select name="discount_type" class="form-control">
                        <option value="">Discount Types</option>
                        <option value="percent"
                            {{ request('discount_type') == 'percent' ? 'selected' : '' }}>
                            Percent
                        </option>
                        <option value="fixed"
                            {{ request('discount_type') == 'fixed' ? 'selected' : '' }}>
                            Fixed
                        </option>
                    </select>
                </div>
                <div class="col-md-2 mb-2">
                    <select name="apply_to" class="form-control">
                        <option value="">Apply Coupon To</option>
                        <option value="specific_user"
                            {{ request('apply_to') == 'specific_user' ? 'selected' : '' }}>
                            Specific User</option>
                        <option value="first_user"
                            {{ request('apply_to') == 'first_user' ? 'selected' : '' }}>
                            First User</option>
                        <option value="everyone"
                            {{ request('apply_to') == 'everyone' ? 'selected' : '' }}>
                            Everyone</option>
                    </select>
                </div>
                <div class="col-md-2 mb-2">
                    <input type="date" name="redeemed_from" class="form-control" placeholder="Redeemed From"
                        value="{{ request('redeemed_from') }}">
                </div>
                <div class="col-md-2 mb-2">
                    <input type="date" name="redeemed_to" class="form-control" placeholder="Redeemed To"
                        value="{{ request('redeemed_to') }}">
                </div>
                <div class="d-flex " style="gap:50px;margin: 20px;">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="redeemed" id="redeemed" value="1"
                            {{ request('redeemed') ? 'checked' : '' }}>
                        <label class="form-check-label" for="redeemed">Redeemed</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="extra_discount" id="extra_discount"
                            value="1"
                            {{ request('extra_discount') ? 'checked' : '' }}>
                        <label class="form-check-label" for="extra_discount">Extra Discount</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="available" id="available" value="1"
                            {{ request('available') ? 'checked' : '' }}>
                        <label class="form-check-label" for="available">Available</label>
                    </div>
                </div>

            </div>

            <div class="row align-items-end ml-1 mt-3">

                <button type="submit" class="btn btn-primary mr-3">FILTER</button>


                <a href="?" class="btn btn-secondary ">RESET</a>

            </div>
        </form>
        <div class="panel">
            <!-- Coupon Filters UI -->

            <div class="panel-body">
                <form action="" class="bravo-form-item">
                    <div class="table-responsive">
                        <table class="table table-hover cart-table">
                            <thead>
                                <tr>
                                    <!-- <th width="45px"><input type="checkbox" class="check-all"></th> -->
                                    <th>{{ __('Name') }}</th>
                                    <th>{{ __('Code') }}</th>
                                    <th>{{ __('Amount') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Created On') }}</th>
                                    <th>{{ __('Validity Date') }}</th>
                                    <th>{{ __('Validity time') }}</th>
                                    <th>{{ __('Used') }}</th>
                                    <th width="100px">{{ __('Operations') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($rows->total() > 0)


                                    @foreach($rows as $row)
                                        <tr style=" align-content: center; align-items: center;"
                                            class="{{ $row->status }}">
                                            <!-- <td><input type="checkbox" name="ids[]" class="check-item" value="{{ $row->id }}"></td> -->
                                            <td style=" align-content: center; align-items: center;">
                                                {{ $row->activity_name ?? __('Unlimited') }}
                                            </td>
                                            <td class="title"
                                                style=" align-content: center; align-items: center;color: #60a5fa !important;">
                                                <strong>{{ $row->code }}</strong></td>
                                            <td style=" align-content: center; align-items: center;">
                                                @if($row->discount_type == 'percent')
                                                    {{ $row->amount }}<span style="color:#aaa; font-size:13px;">
                                                        %</span>
                                                @else
                                                    {!! format_money($row->amount) !!}
                                                @endif
                                            </td>
                                            <td style=" align-content: center; align-items: center;"><span
                                                    class="badge badge-{{ $row->status }}"
                                                    style="font-size: 14px; padding: 6px 12px;border-radius: 13px;">{{ $row->status == 'publish' ? 'Active' : 'Inactive' }}</span>
                                            </td>
                                            <td style=" align-content: center; align-items: center;">
                                                @if($row->created_at)
                                                    {{ $row->created_at->format('d/M/Y') }}<br>
                                                    <span>{{ $row->created_at->format('H:i') }}</span>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td style=" align-content: center; align-items: center;">
                                                {{ $row->end_date ? \Carbon\Carbon::parse($row->end_date)->format('d/M/Y') : '-' }}
                                            </td>


                                            <td style=" align-content: center; align-items: center;">
                                                {{ $row->limit_per_user ?: __('Unlimited') }}
                                            </td>
                                            <td style=" align-content: center; align-items: center;">
                                                {{ \Modules\Coupon\Models\CouponBookings::where('coupon_code', $row->code)->whereNotIn('booking_status', ['draft', 'unpaid', 'cancelled'])->count() }}
                                            </td>
                                            <td style=" align-content: center; align-items: center;">
                                                <div style="display: flex; align-items: center; gap: 8px;">
                                                    <a href="{{ route('coupon.admin.edit', ['id' => $row->id]) }}"
                                                        class="coupon-edit-btn" title="Edit">
                                                        <i class="fa fa-cog" style="font-size:18px;"></i>
                                                    </a>
                                                    <form
                                                        action="{{ route('coupon.admin.delete', ['id' => $row->id]) }}"
                                                        method="POST" style="display:inline-block; margin:0;"
                                                        onsubmit="return confirm('Are you sure you want to delete this promotion?');">
                                                        @csrf
                                                        <button type="submit" class="btn "
                                                            title="{{ __('Delete') }}"><i
                                                                class="fa fa-times"
                                                                style="font-size:20px;color:#ff3366;"></i></button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="10">{{ __('No coupon found') }}</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

@endsection
