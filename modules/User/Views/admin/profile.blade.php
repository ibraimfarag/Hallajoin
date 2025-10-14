@extends('admin.layouts.app')

@section('content')
    <!-- Add Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Add Flag Icons CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icons/7.5.0/css/flag-icons.min.css">

    <style>
        /* Light Mode & Dark Mode Variables */
        :root {
            /* Dark Mode Colors (Default) */
            --bg-primary: #132439;
            --bg-secondary: rgba(255, 255, 255, 0.05);
            --bg-hover: rgba(255, 255, 255, 0.03);
            --text-primary: #ffffff;
            --text-secondary: #a0aec0;
            --text-muted: #718096;
            --border-color: rgba(255, 255, 255, 0.1);
            --border-light: rgba(255, 255, 255, 0.2);
            --shadow: rgba(0, 0, 0, 0.3);
        }

        /* Light Mode Override */
        [data-theme="light"] {
            --bg-primary: #ffffff;
            --bg-secondary: #f7fafc;
            --bg-hover: #edf2f7;
            --text-primary: #1a202c;
            --text-secondary: #4a5568;
            --text-muted: #718096;
            --border-color: #e2e8f0;
            --border-light: #cbd5e0;
            --shadow: rgba(0, 0, 0, 0.1);
        }

        .profile-header {
            background: var(--bg-primary);
            border-radius: 16px;
            padding: 24px;
            margin-bottom: 24px;
            position: relative;
            overflow: hidden;
            border: 1px solid var(--border-color);
            transition: all 0.3s ease;
        }



        .profile-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            border: 4px solid rgba(255, 255, 255, 0.2);
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            font-weight: 700;
            color: white;
            margin-bottom: 16px;
            margin-top: 40px;
        }

        .country-flag {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: var(--bg-secondary);
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 14px;
            margin-bottom: 8px;
            margin-left: -15px;
            border: 1px solid var(--border-color);
        }

        .country-flag .fi {
            font-size: 16px !important;
            line-height: 1;
            border-radius: 2px;
            display: inline-block;
            width: 18px;
            height: 12px;
        }

        .profile-phone {
            color: var(--text-secondary);
            font-size: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .profile-name {
            font-size: 16px;
            margin: 8px 0;
            color: var(--text-secondary);
        }


        .profile-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
            margin-top: 16px;
        }

        .info-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0;
        }

        .info-label {
            color: var(--text-secondary);
            font-size: 14px;
        }

        .info-value {
            color: var(--text-primary);
            font-weight: 600;
            font-size: 14px;
        }

        .status-badges {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            margin-top: 16px;
        }

        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 15px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .badge-success {
            background: rgba(72, 187, 120, 0.2);
            color: #68d391;
        }

        .badge-warning {
            background: rgba(236, 201, 75, 0.2);
            color: #f6e05e;
        }

        .badge-primary {
            background: rgba(66, 153, 225, 0.2);
            color: #63b3ed;
        }

        .badge-secondary {
            background: rgba(113, 128, 150, 0.2);
            color: #a0aec0;
        }

        .section-card {
            background: var(--bg-primary);
            border-radius: 16px;
            padding: 24px;
            margin-bottom: 24px;
            border: 1px solid var(--border-color);
            transition: all 0.3s ease;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .section-title {
            font-size: 18px;
            font-weight: 700;
            color: var(--text-primary);
        }

        .view-link {
            color: #63b3ed;
            font-size: 14px;
            text-decoration: none;
            font-weight: 600;
        }

        .balance-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
        }

        .balance-item {
            background: var(--bg-secondary);
            border-radius: 12px;
            padding: 16px;
            display: flex;
            align-items: center;
            gap: 12px;
            border: 1px solid var(--border-color);
        }

        .balance-icon {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
        }

        .wallet-icon {
            background: rgba(72, 187, 120, 0.2);
            color: #68d391;
        }

        .points-icon {
            background: rgba(236, 201, 75, 0.2);
            color: #f6e05e;
        }

        .balance-info h4 {
            font-size: 20px;
            font-weight: 700;
            margin: 0;
            color: var(--text-primary);
        }

        .balance-info p {
            font-size: 14px;
            color: var(--text-secondary);
            margin: 0;
        }

        .orders-summary {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 16px;
            margin-bottom: 20px;
        }

        .order-stat {
            text-align: left;
            padding: 16px;
            background: var(--bg-secondary);
            border-radius: 12px;
            border: 1px solid var(--border-color);
        }

        .order-stat-number {
            font-size: 24px;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 4px;
        }

        .order-stat-label {
            font-size: 12px;
            color: var(--text-secondary);
        }

        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .sessions-table,
        .transactions-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 16px;
        }

        .sessions-table {
            min-width: 900px;
            /* Minimum width for sessions table */
        }

        .transactions-table {
            min-width: 750px;
            /* Minimum width for transactions table */
        }

        .sessions-table th,
        .sessions-table td,
        .transactions-table th,
        .transactions-table td {
            padding: 12px 8px;
            text-align: left;
            border-bottom: 1px solid var(--border-color);
            white-space: nowrap;
            /* Prevent text wrapping */
        }

        .sessions-table th,
        .transactions-table th {
            color: var(--text-secondary);
            font-weight: 600;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            background: var(--bg-hover);
        }

        .sessions-table td,
        .transactions-table td {
            color: var(--text-primary);
            font-size: 14px;
            vertical-align: middle;
        }

        .sessions-table tbody tr:hover,
        .transactions-table tbody tr:hover {
            background: var(--bg-hover);
            transition: background 0.2s ease;
        }

        /* Column widths for better layout */
        .sessions-table th:nth-child(1),
        .sessions-table td:nth-child(1) {
            width: 140px;
        }

        /* IP */
        .sessions-table th:nth-child(2),
        .sessions-table td:nth-child(2) {
            width: 120px;
        }

        /* Brand */
        .sessions-table th:nth-child(3),
        .sessions-table td:nth-child(3) {
            width: 120px;
        }

        /* Model */
        .sessions-table th:nth-child(4),
        .sessions-table td:nth-child(4) {
            width: 80px;
        }

        /* Device ID */
        .sessions-table th:nth-child(5),
        .sessions-table td:nth-child(5) {
            width: 100px;
        }

        /* OS */
        .sessions-table th:nth-child(6),
        .sessions-table td:nth-child(6) {
            width: 120px;
        }

        /* Browser */
        .sessions-table th:nth-child(7),
        .sessions-table td:nth-child(7) {
            width: 130px;
        }

        /* Created On */
        .sessions-table th:nth-child(8),
        .sessions-table td:nth-child(8) {
            width: 80px;
        }

        /* Language */
        .sessions-table th:nth-child(9),
        .sessions-table td:nth-child(9) {
            width: 80px;
        }

        /* Actions */

        /* Column widths for transactions table */
        .transactions-table th:nth-child(1),
        .transactions-table td:nth-child(1) {
            width: 180px;
        }

        /* Order */
        .transactions-table th:nth-child(2),
        .transactions-table td:nth-child(2) {
            width: 120px;
        }

        /* Amount */
        .transactions-table th:nth-child(3),
        .transactions-table td:nth-child(3) {
            width: 120px;
        }

        /* Balance */
        .transactions-table th:nth-child(4),
        .transactions-table td:nth-child(4) {
            width: 120px;
        }

        /* Gift Code */
        .transactions-table th:nth-child(5),
        .transactions-table td:nth-child(5) {
            width: 140px;
        }

        /* Point Reason */
        .transactions-table th:nth-child(6),
        .transactions-table td:nth-child(6) {
            width: 130px;
        }

        /* Created On */

        .device-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .device-icon {
            width: 32px;
            height: 32px;
            background: rgba(66, 153, 225, 0.15);
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            color: #63b3ed;
            flex-shrink: 0;
        }

        .device-icon.apple {
            background: rgba(150, 150, 150, 0.15);
            color: #a0aec0;
        }

        .device-icon.android {
            background: rgba(76, 175, 80, 0.15);
            color: #68d391;
        }

        .device-icon.windows {
            background: rgba(33, 150, 243, 0.15);
            color: #63b3ed;
        }

        .amount-positive {
            color: #68d391;
        }

        .amount-negative {
            color: #f56565;
        }

        .back-button {
            background: var(--bg-secondary);
            border: 1px solid var(--border-light);
            color: var(--text-primary);
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-weight: 600;
            transition: all 0.2s ease;
            margin-bottom: 24px;
        }

        .back-button:hover {
            background: var(--bg-hover);
            color: var(--text-primary);
            text-decoration: none;
            box-shadow: 0 2px 8px var(--shadow);
        }

        .order-id {
            color: #63b3ed;
            font-weight: 600;
        }

        .status-success {
            color: #68d391;
        }

        .status-pending {
            color: #f6e05e;
        }

        /* Ensure icons display properly */
        .fas,
        .fa {
            font-family: "Font Awesome 6 Free", "Font Awesome 5 Free" !important;
            font-weight: 900 !important;
            display: inline-block;
        }

        /* Select dropdown styling for Dark/Light mode */
        select#userRole {
            background: var(--bg-secondary) !important;
            border: 1px solid var(--border-color) !important;
            color: var(--text-primary) !important;
            padding: 8px 12px;
            border-radius: 6px;
            font-size: 14px;
            width: 100%;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        select#userRole:hover {
            border-color: var(--border-light);
            background: var(--bg-hover) !important;
        }

        select#userRole:focus {
            outline: none;
            border-color: #63b3ed;
            box-shadow: 0 0 0 3px rgba(99, 179, 237, 0.1);
        }

        /* Options styling for Dark/Light mode */
        select#userRole option {
            background: var(--bg-primary) !important;
            color: var(--text-primary) !important;
            padding: 10px;
        }

        /* For Light Mode - specific browser overrides */
        [data-theme="light"] select#userRole {
            background: #f7fafc !important;
            color: #1a202c !important;
            border-color: #e2e8f0 !important;
        }

        [data-theme="light"] select#userRole option {
            background: #ffffff !important;
            color: #1a202c !important;
        }

        [data-theme="light"] select#userRole:hover {
            background: #edf2f7 !important;
        }

        /* For Dark Mode - specific browser overrides */
        :root select#userRole option,
        html:not([data-theme="light"]) select#userRole option {
            background: #132439 !important;
            color: #ffffff !important;
        }

        /* Smooth transitions for theme changes */
        * {
            transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease;
        }
    </style>

    <div class="profile-container">
        <div class="container-fluid">
            <a href="{{ route('user.admin.index') }}" class="back-button">
                <i class="fas fa-arrow-left"></i>
                {{ __('Back to Users') }}
            </a>

            <div class=" row ml-5 pl-5">
                <!-- Left Side - Profile Info -->
                <div class="col-lg-4">
                    <!-- Profile Header -->
                    <div class="profile-header">
                        <div class="d-flex align-items-start">
                            <div class="me-3">
                                @if($user->avatar)
                                    <img src="{{ $user->avatar }}" alt="avatar" class="profile-avatar">
                                @else
                                    <div class="profile-avatar">
                                        {{ strtoupper(substr($user->first_name ?? $user->name ?? 'U', 0, 1)) }}
                                    </div>
                                @endif
                            </div>
                            <div class="flex-grow-1 pl-4">
                                <div class="country-flag">
                                    @php
                                        // Map country codes to country names and flag codes
                                        $phoneCodeToCountry = [
                                            '971' => ['name' => 'United Arab Emirates', 'flag' => 'ae'],
                                            '966' => ['name' => 'Saudi Arabia', 'flag' => 'sa'],
                                            '965' => ['name' => 'Kuwait', 'flag' => 'kw'],
                                            '974' => ['name' => 'Qatar', 'flag' => 'qa'],
                                            '973' => ['name' => 'Bahrain', 'flag' => 'bh'],
                                            '968' => ['name' => 'Oman', 'flag' => 'om'],
                                            '1' => ['name' => 'United States', 'flag' => 'us'],
                                            '20' => ['name' => 'Egypt', 'flag' => 'eg'],
                                            '961' => ['name' => 'Lebanon', 'flag' => 'lb'],
                                            '962' => ['name' => 'Jordan', 'flag' => 'jo'],
                                            '963' => ['name' => 'Syria', 'flag' => 'sy'],
                                            '964' => ['name' => 'Iraq', 'flag' => 'iq'],
                                            '967' => ['name' => 'Yemen', 'flag' => 'ye'],
                                            '212' => ['name' => 'Morocco', 'flag' => 'ma'],
                                            '213' => ['name' => 'Algeria', 'flag' => 'dz'],
                                            '216' => ['name' => 'Tunisia', 'flag' => 'tn'],
                                            '218' => ['name' => 'Libya', 'flag' => 'ly'],
                                            '249' => ['name' => 'Sudan', 'flag' => 'sd'],
                                        ];

                                        // Try to determine country from phone number first
                                        $country = $user->country ?? 'United Arab Emirates';
                                        $flagCode = 'ae'; // default

                                        if ($user->phone) {
                                            // Extract country code from phone number
                                            $phone = preg_replace('/[^0-9]/', '', $user->phone);

                                            // Check for common country codes
                                            foreach ($phoneCodeToCountry as $code => $countryData) {
                                                if (str_starts_with($phone, $code)) {
                                                    $country = $countryData['name'];
                                                    $flagCode = $countryData['flag'];
                                                    break;
                                                }
                                            }
                                        }

                                        // Fallback to manual country mapping if no phone match
                                        if (!$user->phone || $country == 'United Arab Emirates') {
                                            $countryFlags = [
                                                'United Arab Emirates' => 'ae',
                                                'UAE' => 'ae',
                                                'Saudi Arabia' => 'sa',
                                                'Kuwait' => 'kw',
                                                'Qatar' => 'qa',
                                                'Bahrain' => 'bh',
                                                'Oman' => 'om',
                                                'US' => 'us',
                                                'United States' => 'us',
                                                'Egypt' => 'eg',
                                                'Lebanon' => 'lb',
                                                'Jordan' => 'jo',
                                                'Syria' => 'sy',
                                                'Iraq' => 'iq',
                                                'Yemen' => 'ye',
                                                'Morocco' => 'ma',
                                                'Algeria' => 'dz',
                                                'Tunisia' => 'tn',
                                                'Libya' => 'ly',
                                                'Sudan' => 'sd',
                                            ];

                                            if ($user->country && isset($countryFlags[$user->country])) {
                                                $country = $user->country;
                                                $flagCode = $countryFlags[$user->country];
                                            }
                                        }
                                    @endphp
                                    <span class="fi fi-{{ $flagCode }}"
                                        style="font-size: 18px; line-height: 1; margin-right: 3px;"></span>
                                    <span style="color: var(--text-secondary);">{{ $country }}</span>
                                </div>
                                <div class="profile-phone">
                                    <i class="fas fa-phone" style="font-size: 14px; color: var(--text-secondary);"></i>
                                    {{ $user->phone ?? 'No phone provided' }}
                                </div>
                                <div class="profile-name">
                                    <i class="fas fa-user"
                                        style="margin-right: 8px; color: var(--text-secondary);"></i>{{ $user->getDisplayName() }}
                                </div>

                                <div class="status-badges">
                                    @if($user->status == 'publish')
                                        <span class="status-badge badge-success">✓ {{ __('Active') }}</span>
                                    @else
                                        <span class="status-badge badge-warning">⚠ {{ __('Inactive') }}</span>
                                    @endif

                                    @if($user->email_verified_at)
                                        <span class="status-badge badge-success">✓ {{ __('Email Verified') }}</span>
                                    @else
                                        <span class="status-badge badge-warning">⚠ {{ __('Email Not Verified') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Balance Section -->
                    <div class="section-card">
                        <div class="section-header">
                            <h3 class="section-title">{{ __('Balance') }}</h3>
                            <div style="color: var(--text-secondary); font-size: 18px; font-weight: 700;">
                                {{ number_format($balance, 2) }}{!! get_current_currency_svg() !!}
                            </div>
                        </div>
                        <div class="balance-grid">
                            <div class="balance-item">
                                <div class="balance-icon wallet-icon">
                                    <i class="fas fa-wallet"></i>
                                </div>
                                <div class="balance-info">
                                    <p>{{ __('Wallet') }}:
                                        {{ number_format($balance, 2) }}{!! get_current_currency_svg() !!}
                                    </p>
                                </div>
                            </div>
                            <div class="balance-item">
                                <div class="balance-icon points-icon">
                                    <i class="fas fa-star"></i>
                                </div>
                                <div class="balance-info">
                                    <p>{{ __('Points') }}: {{ $points }}
                                        ({{ number_format($pointsValue, 2) }}{!! get_current_currency_svg() !!})</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Orders Section -->
                    <div class="section-card">
                        <div class="section-header">
                            <h3 class="section-title">{{ __('Orders') }}</h3>
                            <a href="#" class="view-link">{{ __('VIEW') }}</a>
                        </div>

                        <div class="orders-summary">
                            <div style="margin-bottom: 16px;">
                                <div style="color: var(--text-primary); font-size: 18px; font-weight: 700;">
                                    {{ $totalOrders }}
                                    {{ __('orders') }}
                                </div>
                                <div style="color: var(--text-secondary); font-size: 14px;">{{ __('Total Amount') }}:
                                    {{ number_format($totalAmount, 2) }}{!! get_current_currency_svg() !!}
                                </div>
                                <div style="color: var(--text-secondary); font-size: 14px;">{{ __('Refunded') }}:
                                    {{ number_format($totalRefunded, 2) }}{!! get_current_currency_svg() !!}
                                </div>
                            </div>
                        </div>

                        @if($lastOrder)
                            <div style="text-align: left; padding: 20px; color: var(--text-secondary);">
                                <div style="margin-bottom: 12px;">{{ __('Last order') }}:</div>
                                <div
                                    style="background: var(--bg-secondary); padding: 16px; border-radius: 8px; border: 1px solid var(--border-color);">
                                    <!-- Order ID - Main Line -->
                                    <div style="margin-bottom: 12px;">
                                        <span
                                            style="color: #63b3ed; font-size: 16px; font-weight: 600;">#{{ $lastOrder->code ?? $lastOrder->id }}</span>
                                    </div>

                                    <!-- Order Details - Secondary Lines -->
                                    <div style="display: flex; flex-direction: column; gap: 6px;">
                                        <div style="display: flex; justify-content: space-between; align-items: center;">
                                            <span
                                                style="color: var(--text-secondary); font-size: 14px;">{{ __('Date') }}:</span>
                                            <span
                                                style="color: var(--text-primary); font-size: 14px;">{{ $lastOrder->created_at->format('d/M/Y H:i') }}</span>
                                        </div>

                                        <div style="display: flex; justify-content: space-between; align-items: center;">
                                            <span
                                                style="color: var(--text-secondary); font-size: 14px;">{{ __('Amount') }}:</span>
                                            <span
                                                style="color: #68d391; font-size: 14px; font-weight: 600;">{{ number_format($lastOrder->total, 2) }}{!! get_current_currency_svg() !!}</span>
                                        </div>

                                        <div style="display: flex; justify-content: space-between; align-items: center;">
                                            <span
                                                style="color: var(--text-secondary); font-size: 14px;">{{ __('Status') }}:</span>
                                            <span
                                                style="background: {{ $lastOrder->status == 'completed' ? '#28a745' : ($lastOrder->status == 'pending' ? '#ffc107' : '#6c757d') }}; color: white; padding: 4px 8px; border-radius: 12px; font-size: 11px; font-weight: 600; text-transform: uppercase;">
                                                {{ ucfirst($lastOrder->status) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div style="text-align: center; padding: 20px; color: var(--text-secondary);">
                                {{ __('No orders yet') }}
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Right Side - Profile Details -->
                <div class="col-lg-6">
                    <!-- Profile Details Card -->
                    <div class="section-card" style="height: fit-content;">
                        <div class="section-header">
                            <h3 class="section-title">{{ __('Profile') }}</h3>
                        </div>

                        <div class="profile-info" style="display: block;">
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                                <!-- Left Column -->
                                <div>
                                    <div style="margin-bottom: 12px;">
                                        <span class="info-label">{{ __('Register date') }}:</span>
                                        <span class="info-value">{{ $user->created_at->format('d/M/Y H:i') }}</span>
                                    </div>

                                    <div style="margin-bottom: 12px;">
                                        <span class="info-label">{{ __('Referral Code') }}:</span>
                                        <span class="info-value">{{ $user->getMeta('referral_code', 'N/A') }}</span>
                                    </div>

                                    <div style=" margin: 20px 0;display: flex;gap: 20px;">
                                        <div style="margin-bottom: 8px;">
                                            <input type="checkbox" id="userBlocked" style="margin-right: 8px;" {{ $user->blocked ? 'checked' : '' }}
                                                onchange="toggleUserBlock({{ $user->id }}, this.checked)">
                                            <label for="userBlocked"
                                                style="color: var(--text-secondary); cursor: pointer;">{{ __('Block User') }}</label>
                                        </div>
                                        <div style="margin-bottom: 8px;">
                                            <input type="checkbox" id="orderBlocked" style="margin-right: 8px;" {{ $user->order_blocked ? 'checked' : '' }}
                                                onchange="toggleOrderBlock({{ $user->id }}, this.checked)">
                                            <label for="orderBlocked"
                                                style="color: var(--text-secondary); cursor: pointer;">{{ __('Block Orders') }}</label>
                                        </div>

                                    </div>

                                </div>

                                <!-- Right Column -->
                                <div>
                                    <div style="margin-bottom: 12px;">
                                        <span class="info-label">{{ __('Email') }}:</span>
                                        <span class="info-value">{{ $user->email }}</span>
                                    </div>

                                    <div style="margin-bottom: 12px;">
                                        <span class="info-label">{{ __('Gender') }}:</span>
                                        <span
                                            class="info-value">{{ $user->gender ? __(ucfirst($user->gender)) : __('Not Specified') }}</span>
                                    </div>

                                    <div style="margin-bottom: 12px;">
                                        <span class="info-label">{{ __('Birthdate') }}:</span>
                                        <span
                                            class="info-value">{{ $user->birthday ? \Carbon\Carbon::parse($user->birthday)->format('d/M/Y') : __('Not Specified') }}</span>
                                    </div>
                                </div>
                            </div>


                            <!-- Bottom section - Roles -->
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-top: 20px;">
                                <div>
                                    <span class="info-label"
                                        style="display: block; margin-bottom: 8px;">{{ __('User Role') }}:</span>
                                    <select id="userRole" class="form-control"
                                        onchange="updateUserRole({{ $user->id }}, this.value)"
                                        data-original="{{ $user->role_id }}">
                                        @foreach($roles as $role)
                                            <option value="{{ $role->id }}" {{ ($user->role_id == $role->id) ? 'selected' : '' }}>
                                                {{ $role->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                            </div>
                        </div>




                    </div>
                    <div class="section-card" style="height: fit-content;">
                        <div class="section-header">
                            <h3 class="section-title">{{ __('Sessions') }}</h3>
                            <span style="color: var(--text-secondary); font-size: 14px;">{{ $sessions->count() }}
                                {{ __('Sessions') }}</span>
                        </div>





                        <div class="table-responsive">
                            <table class="sessions-table">
                                <thead>
                                    <tr>
                                        <th>{{ __('IP') }}</th>
                                        <th>{{ __('Brand') }}</th>
                                        <th>{{ __('Model') }}</th>
                                        <th>{{ __('Device Id') }}</th>
                                        <th>{{ __('OS') }}</th>
                                        <th>{{ __('Browser') }}</th>
                                        <th>{{ __('Created On') }}</th>
                                        <th>{{ __('Language') }}</th>
                                        <th>{{ __('Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($sessions as $session)
                                        <tr data-session-id="{{ $session->id }}">
                                            <td>
                                                <span style="font-family: 'Courier New', monospace; color: #63b3ed;">
                                                    {{ $session->ip_address ?? 'N/A' }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="device-info">
                                                    @php
                                                        $brand = strtolower($session->brand ?? '');
                                                        $iconClass = 'laptop';
                                                        $deviceClass = '';

                                                        if (str_contains($brand, 'apple')) {
                                                            $iconClass = 'apple';
                                                            $deviceClass = 'apple';
                                                        } elseif (str_contains($brand, 'android') || str_contains($brand, 'samsung') || str_contains($brand, 'xiaomi')) {
                                                            $iconClass = 'android';
                                                            $deviceClass = 'android';
                                                        } elseif (str_contains($brand, 'pc') || str_contains($brand, 'windows') || str_contains($brand, 'microsoft')) {
                                                            $iconClass = 'windows';
                                                            $deviceClass = 'windows';
                                                        }
                                                    @endphp
                                                    <div class="device-icon {{ $deviceClass }}">
                                                        <i class="fab fa-{{ $iconClass }}"></i>
                                                    </div>
                                                    <span>{{ $session->brand ?? 'Unknown' }}</span>
                                                </div>
                                            </td>
                                            <td>{{ $session->model ?? 'Unknown' }}</td>
                                            <td style="text-align: center;">
                                                @if($session->device_id)
                                                    <i class="fas fa-copy" style="color: #63b3ed; cursor: pointer; font-size: 16px;"
                                                        title="{{ $session->device_id }}"
                                                        onclick="copyToClipboard('{{ $session->device_id }}')"></i>
                                                @else
                                                    <span style="color: #4a5568;">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span
                                                    style="color: var(--text-secondary);">{{ $session->os ?? 'Unknown' }}</span>
                                            </td>
                                            <td>
                                                <span
                                                    style="color: var(--text-secondary);">{{ $session->browser ?? 'Unknown' }}</span>
                                            </td>
                                            <td>
                                                <div style="line-height: 1.4;">
                                                    <div style="color: var(--text-primary);">
                                                        {{ $session->last_activity ? $session->last_activity->format('d/M/Y') : 'N/A' }}
                                                    </div>
                                                    <div style="color: var(--text-secondary); font-size: 12px;">
                                                        {{ $session->last_activity ? $session->last_activity->format('H:i') : '' }}
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span
                                                    style="text-transform: uppercase; color: var(--text-secondary); font-weight: 600; font-size: 12px;">
                                                    {{ $session->language ?? 'en' }}
                                                </span>
                                            </td>
                                            <td>
                                                <button onclick="deleteSession({{ $session->id }})"
                                                    class="btn btn-sm btn-danger"
                                                    style="background: #dc3545; border: none; color: white; padding: 6px 12px; border-radius: 4px; cursor: pointer; font-size: 12px;"
                                                    title="Delete Session">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9"
                                                style="text-align: center; color: var(--text-secondary); padding: 40px;">
                                                <i class="fas fa-inbox"
                                                    style="font-size: 48px; margin-bottom: 16px; opacity: 0.3;"></i>
                                                <div>{{ __('No sessions found') }}</div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>




                    </div>



                    <div class="section-card" style="height: fit-content;">
                        <div class="section-header">
                            <h3 class="section-title">{{ __('Transactions') }}</h3>
                        </div>


                        <div class="table-responsive">
                            <table class="transactions-table">
                                <thead>
                                    <tr>
                                        <th>{{ __('Order') }}</th>
                                        <th>{{ __('Amount') }}</th>
                                        <th>{{ __('Balance') }}</th>
                                        <th>{{ __('Gift Code') }}</th>
                                        <th>{{ __('Point Reason') }}</th>
                                        <th>{{ __('Created On') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($orders->take(10) as $order)
                                        <tr>
                                            <td>
                                                <span class="order-id">#{{ $order->code ?? $order->id }}</span>
                                            </td>
                                            <td>
                                                <div>
                                                    <div
                                                        style="color: {{ $order->status == 'cancelled' ? '#f56565' : '#68d391' }};">
                                                        {{ $order->status == 'cancelled' ? '-' : '' }}{{ number_format($order->total, 2) }}{!! get_current_currency_svg() !!}
                                                    </div>
                                                    @if($order->wallet_credit_used > 0)
                                                        <div style="color: #a0aec0; font-size: 12px;">
                                                            ({{ $order->status == 'cancelled' ? '+' : '-' }}{{ number_format($order->wallet_credit_used, 3) }})
                                                        </div>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>{{ number_format($order->paid ?? 0, 2) }}{!! get_current_currency_svg() !!}</td>
                                            <td>{{ $order->coupon_amount > 0 ? number_format($order->coupon_amount, 2) . get_current_currency_svg() : '' }}
                                            </td>
                                            <td>{{ $order->status == 'cancelled' ? 'Refund' : 'OrderPayment' }}</td>
                                            <td>{{ $order->created_at->format('d/M/Y') }}<br>{{ $order->created_at->format('H:i') }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" style="text-align: center; color: var(--text-secondary);">
                                                {{ __('No transactions found') }}
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>



                    </div>
                </div>
            </div>

            <!-- Bottom Section - Sessions and Transactions -->

        </div>
    </div>

    <script>
        // Copy to clipboard function with better UX
        function copyToClipboard(text) {
            if (!text) return;

            navigator.clipboard.writeText(text).then(function () {
                // Create and show toast notification
                const toast = document.createElement('div');
                toast.innerHTML = '<i class="fas fa-check-circle"></i> Device ID copied!';
                toast.style.cssText = 'position:fixed;top:20px;right:20px;background:#10b981;color:white;padding:12px 20px;border-radius:8px;box-shadow:0 4px 12px rgba(0,0,0,0.3);z-index:9999;display:flex;align-items:center;gap:8px;font-weight:600;animation:slideIn 0.3s ease;';
                document.body.appendChild(toast);

                setTimeout(function () {
                    toast.style.animation = 'slideOut 0.3s ease';
                    setTimeout(function () {
                        document.body.removeChild(toast);
                    }, 300);
                }, 2000);
            }).catch(function (err) {
                console.error('Failed to copy:', err);
                alert('Failed to copy Device ID');
            });
        }

        // Delete session function
        function deleteSession(sessionId) {
            if (confirm('{{ __("Are you sure you want to delete this session?") }}')) {
                fetch(`{{ route('admin.user.session.delete', ['id' => ':sessionId']) }}`.replace(':sessionId', sessionId), {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                })
                    .then(response => {
                        if (response.ok) {
                            return response.json();
                        }
                        throw new Error('Network response was not ok');
                    })
                    .then(data => {
                        if (data.success) {
                            // Remove the row from table
                            const row = document.querySelector(`tr[data-session-id="${sessionId}"]`);
                            if (row) {
                                row.remove();
                            } else {
                                // Fallback: reload page
                                location.reload();
                            }

                            // Show success message
                            showToast('{{ __("Session deleted successfully") }}', 'success');

                            // Update session count if exists
                            const sessionCountElement = document.querySelector('.sessions-count');
                            if (sessionCountElement) {
                                const currentCount = parseInt(sessionCountElement.textContent) - 1;
                                sessionCountElement.textContent = currentCount;
                            }
                        } else {
                            showToast(data.message || '{{ __("Failed to delete session") }}', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showToast('{{ __("An error occurred while deleting the session") }}', 'error');
                    });
            }
        }

        // Enhanced toast function
        function showToast(message, type = 'success') {
            const toast = document.createElement('div');
            toast.style.cssText = `
                                                position: fixed;
                                                top: 20px;
                                                right: 20px;
                                                background: ${type === 'success' ? '#28a745' : '#dc3545'};
                                                color: white;
                                                padding: 16px 24px;
                                                border-radius: 8px;
                                                box-shadow: 0 4px 12px rgba(0,0,0,0.3);
                                                z-index: 10000;
                                                font-size: 14px;
                                                font-weight: 500;
                                                animation: slideIn 0.3s ease;
                                                max-width: 300px;
                                                word-wrap: break-word;
                                            `;
            toast.textContent = message;
            document.body.appendChild(toast);

            setTimeout(function () {
                toast.style.animation = 'slideOut 0.3s ease';
                setTimeout(function () {
                    if (document.body.contains(toast)) {
                        document.body.removeChild(toast);
                    }
                }, 300);
            }, 3000);
        }



        // Toggle user block/unblock
        function toggleUserBlock(userId, isBlocked) {
            const checkbox = document.getElementById('userBlocked');
            checkbox.disabled = true; // Disable during request

            fetch(`{{ route('admin.user.toggle.block', ['id' => ':userId']) }}`.replace(':userId', userId), {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    blocked: isBlocked
                })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showToast(data.message, 'success');
                    } else {
                        // Revert checkbox state on error
                        checkbox.checked = !isBlocked;
                        showToast(data.message || '{{ __("Failed to update block status") }}', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    // Revert checkbox state on error
                    checkbox.checked = !isBlocked;
                    showToast('{{ __("An error occurred while updating block status") }}', 'error');
                })
                .finally(() => {
                    checkbox.disabled = false; // Re-enable checkbox
                });
        }

        // Toggle order block/unblock  
        function toggleOrderBlock(userId, isBlocked) {
            const checkbox = document.getElementById('orderBlocked');
            checkbox.disabled = true; // Disable during request

            fetch(`{{ route('admin.user.toggle.order.block', ['id' => ':userId']) }}`.replace(':userId', userId), {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    order_blocked: isBlocked
                })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showToast(data.message, 'success');
                    } else {
                        // Revert checkbox state on error
                        checkbox.checked = !isBlocked;
                        showToast(data.message || '{{ __("Failed to update order block status") }}', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    // Revert checkbox state on error
                    checkbox.checked = !isBlocked;
                    showToast('{{ __("An error occurred while updating order block status") }}', 'error');
                })
                .finally(() => {
                    checkbox.disabled = false; // Re-enable checkbox
                });
        }

        // Update user role
        function updateUserRole(userId, roleId) {
            const select = document.getElementById('userRole');
            const originalValue = select.dataset.original || select.value;
            select.disabled = true; // Disable during request

            fetch(`{{ route('admin.user.update.role', ['id' => ':userId']) }}`.replace(':userId', userId), {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    role_id: roleId
                })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showToast(data.message || '{{ __("User role updated successfully") }}', 'success');
                        select.dataset.original = roleId; // Store new value
                    } else {
                        // Revert select state on error
                        select.value = originalValue;
                        showToast(data.message || '{{ __("Failed to update user role") }}', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    // Revert select state on error
                    select.value = originalValue;
                    showToast('{{ __("An error occurred while updating user role") }}', 'error');
                })
                .finally(() => {
                    select.disabled = false; // Re-enable select
                });
        }

        // Add CSS animations
        const style = document.createElement('style');
        style.textContent = `
                                            @keyframes slideIn {
                                                from { transform: translateX(400px); opacity: 0; }
                                                to { transform: translateX(0); opacity: 1; }
                                            }
                                            @keyframes slideOut {
                                                from { transform: translateX(0); opacity: 1; }
                                                to { transform: translateX(400px); opacity: 0; }
                                            }
                                        `;
        document.head.appendChild(style);

        // Auto-detect theme from header switcher
        document.addEventListener('DOMContentLoaded', function () {
            const html = document.documentElement;

            // Check and apply theme
            function applyTheme() {
                // Get saved theme from localStorage (same as header switcher)
                const savedTheme = localStorage.getItem('admin-theme');

                // Check if dark-mode class exists on body or html
                const hasDarkClass = document.body.classList.contains('dark-mode') ||
                    html.classList.contains('dark-mode') ||
                    html.classList.contains('dark-mode-instant');

                // Apply light mode only if theme is explicitly light and no dark class
                if (savedTheme === 'light' && !hasDarkClass) {
                    html.setAttribute('data-theme', 'light');
                } else if (savedTheme === 'dark' || hasDarkClass) {
                    html.removeAttribute('data-theme');
                } else {
                    // Default to light if no preference
                    html.setAttribute('data-theme', 'light');
                }
            }

            // Apply theme immediately
            applyTheme();

            // Monitor for theme changes on body and html
            const observer = new MutationObserver(applyTheme);

            observer.observe(document.body, {
                attributes: true,
                attributeFilter: ['class']
            });

            observer.observe(html, {
                attributes: true,
                attributeFilter: ['class']
            });

            // Listen for localStorage changes (for theme switcher)
            window.addEventListener('storage', function (e) {
                if (e.key === 'admin-theme') {
                    applyTheme();
                }
            });

            // Also check periodically (as backup)
            setInterval(applyTheme, 500);
        });
    </script>
@endsection