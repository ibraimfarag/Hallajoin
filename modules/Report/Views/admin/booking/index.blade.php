@extends('admin.layouts.app')

@push('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- CSS Files -->
    <link rel="stylesheet" href="{{ asset('css/admin/booking/sales-dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/booking/modal-components.css') }}">
@endpush

@section('content')
    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loading-overlay">
        <div class="loading-spinner"></div>
    </div>

    <!-- Notes Sidebar -->
    <div id="notesSidebar" class="notes-sidebar">
        <div class="notes-sidebar-overlay" onclick="closeNotesSidebar()"></div>
        <div class="notes-sidebar-content">
            <div class="notes-sidebar-header">
                <h3 class="notes-sidebar-title">
                    <i class="fa fa-sticky-note"></i>
                    {{ __('Notes for Order') }} <span id="orderNoteNumber">#</span>
                </h3>
                <button class="notes-close-btn" onclick="closeNotesSidebar()">
                    <i class="fa fa-times"></i>
                </button>
            </div>
            <!-- Add Note Section (Top) -->
            <div class="notes-sidebar-footer">
                <div class="notes-add-section">
                    <div style="position: relative;">
                        <textarea id="sidebarNoteTextarea" class="sidebar-note-input" rows="4"
                            placeholder="{{ __('Add a note...') }}"></textarea>

                        <!-- Mention Dropdown -->
                        <div id="mentionDropdown" class="mention-dropdown">
                            <div class="mention-dropdown-header">
                                <input type="text" id="mentionSearch" class="mention-search"
                                    placeholder="{{ __('Search users...') }}" oninput="filterMentionUsers()">
                                <small
                                    style="color: var(--sales-text-secondary); font-size: 11px; margin-top: 8px; display: block;">
                                    <span id="mentionUsersCount">0 {{ __('users') }}</span> {{ __('available') }}
                                </small>
                            </div>
                            <div class="mention-dropdown-body">
                                <div id="mentionUsersList">
                                    <!-- Users will be loaded here -->
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- File Preview -->
                    <div id="attachmentPreview" class="attachment-preview"></div>

                    <div style="display: flex; gap: 10px; align-items: center; margin-top: 15px;">
                        <button type="button" class="btn-mention" onclick="toggleMentionDropdown()">
                            <i class="fa fa-at"></i> {{ __('Mention') }}
                        </button>
                        <button type="button" class="btn-attach"
                            onclick="document.getElementById('noteAttachments').click()">
                            <i class="fa fa-paperclip"></i>
                        </button>
                        <input type="file" id="noteAttachments" multiple accept="image/*,.pdf,.doc,.docx,.txt"
                            style="display: none;">
                        <button type="button" class="btn-submit-note" onclick="saveNoteSidebar()">
                            {{ __('Add Note') }}
                        </button>
                    </div>
                </div>
            </div>

            <!-- Notes Timeline (Bottom) -->
            <div class="notes-sidebar-body">
                <div class="notes-timeline" id="notesTimeline">
                    <!-- Notes will be loaded here -->
                </div>
            </div>
        </div>
    </div>

    <!-- CSS has been moved to external files -->

    <div class="sales-container">
        <!-- Page Title -->
        <div class="orders-header">
            <h1 class="orders-title">{{ __('Sales') }}</h1>
            @if(!empty($booking_update))
                <a href="{{ route('report.admin.booking.create') }}" class="btn-create-order"
                    style="background: transparent; border: 2px solid #059669; color: #059669; padding: 10px 20px; border-radius: 15px; font-weight: 600; font-size: 14px; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; transition: all 0.3s ease; text-decoration: none;"
                    onmouseover="this.style.background='#059669'; this.style.color='white';"
                    onmouseout="this.style.background='transparent'; this.style.color='#059669';">
                    <i class="fa fa-shopping-cart"></i>
                    {{ __('CREATE ORDER') }}
                </a>
            @endif
        </div>

        @include('admin.message')

        <!-- Advanced Filters -->
        <form method="get" action="{{ route('report.admin.booking') }}" id="sales-filter-form">
            <div class="sales-filters">
                <!-- Row 1 -->
                <div class="filter-row">
                    <input type="text" name="order_number" class="filter-input" placeholder="{{ __('Order Number') }}"
                        value="{{ $filters['order_number'] ?? '' }}">

                    <input type="date" name="from_date" class="filter-input" placeholder="{{ __('From') }}"
                        value="{{ $filters['from_date'] ?? '' }}">

                    <input type="date" name="to_date" class="filter-input" placeholder="{{ __('To') }}"
                        value="{{ $filters['to_date'] ?? '' }}">

                    <select name="order_status" class="filter-select">
                        <option value="">{{ __('Order Status') }}</option>
                        @if(!empty($statues))
                            @foreach($statues as $status)
                                <option value="{{ $status }}" {{ ($filters['order_status'] ?? '') == $status ? 'selected' : '' }}>
                                    {{ booking_status_to_text($status) }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                    <select name="confirm_type" class="filter-select">
                        <option value="">{{ __('Confirm Status') }}</option>
                        @foreach($confirmTypes as $key => $name)
                            <option value="{{ $key }}" {{ ($filters['confirm_type'] ?? '') == $key ? 'selected' : '' }}>
                                {{ $name }}
                            </option>
                        @endforeach
                    </select>
                    <select name="gateway" class="filter-select">
                        <option value="">{{ __('Gateway') }}</option>
                        @foreach($gateways as $key => $name)
                            <option value="{{ $key }}" {{ ($filters['gateway'] ?? '') == $key ? 'selected' : '' }}>
                                {{ $name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Row 2 -->
                <div class="filter-row">


                    <input type="text" name="phone_number" class="filter-input" placeholder="{{ __('Phone Number') }}"
                        value="{{ $filters['phone_number'] ?? '' }}">

                    <input type="text" name="reservation" class="filter-input" placeholder="{{ __('Reservation') }}"
                        value="{{ $filters['reservation'] ?? '' }}">

                    <input type="text" name="activity" class="filter-input" placeholder="{{ __('Activity') }}"
                        value="{{ $filters['activity'] ?? '' }}">



                    <select name="salesman_id" class="filter-select">
                        <option value="">{{ __('Salesman') }}</option>
                        <option value="0" {{ ($filters['salesman_id'] ?? '') === '0' ? 'selected' : '' }}>
                            {{ __('Not Assigned') }}
                        </option>
                        @foreach($salesmen as $id => $name)
                            <option value="{{ $id }}" {{ ($filters['salesman_id'] ?? '') == $id ? 'selected' : '' }}>
                                {{ $name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Filter Actions -->
                <div class="filter-actions">
                    <button type="submit" class="btn-filter">{{ __('FILTER') }}</button>
                    <button type="button" class="btn-reset"
                        onclick="window.location.href='{{ route('report.admin.booking') }}'">
                        {{ __('RESET') }}
                    </button>
                </div>
            </div>
        </form>


        <!-- Orders Table -->
        <div class="sales-table-container">
            <table class="sales-table">
                <thead>
                    <tr>
                        <th style="width: 50px;"></th>
                        <th class="sortable" data-sort="code">{{ __('Order') }}</th>
                        <th>{{ __('Activity') }}</th>
                        <th class="sortable" data-sort="total">{{ __('Amount') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th class="sortable" data-sort="created_at">
                            {{ __('Created On') }}
                         
                        </th>
                        <th>{{ __('Confirm Status') }}</th>
                        <th>{{ __('Salesman') }}</th>
                        <th>{{ __('User') }}</th>
                        <th style="width: 80px; text-align: center;">{{ __('Notes') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rows as $booking)
                        <tr class="booking-row" data-booking-id="{{ $booking->id }}">
                            <td>
                                <i class="fa fa-angle-right expand-icon" onclick="toggleDetails({{ $booking->id }})"></i>
                            </td>
                            <td>
                                <a href="#" class="order-link" onclick="toggleDetails({{ $booking->id }}); return false;">
                                    {{ $booking->code ?? $booking->id }}
                                </a>
                            </td>
                            <td>
                                @if($service = $booking->service)
                                    {{ Str::limit($service->title, 30) }}
                                @else
                                    <span style="color: var(--sales-text-secondary);">{{ __('[Deleted]') }}</span>
                                @endif
                            </td>
                            <td>
                                @php
                                    // Calculate cart total (all bookings with same payment_id)
                                    $cartTotal = $booking->total;
                                    if ($booking->payment_id) {
                                        $cartTotal = \Modules\Booking\Models\Booking::where('payment_id', $booking->payment_id)->sum('total');
                                    }
                                @endphp
                                <div style="display: flex; align-items: center;">
                                    <strong style="font-size: 20px;">{{ $cartTotal }}</strong>
                                    <span style="opacity: 0.8; font-size: 16px;">{!! get_current_currency_svg() !!}</span>
                                </div>
                            </td>
                            <td style="text-align: center;">
                                @php
                                    $statusClass = '';
                                    switch ($booking->status) {
                                        case 'cancelled':
                                            $statusClass = 'badge rounded-pill bg-danger';
                                            break;
                                        case 'success':
                                            $statusClass = 'badge rounded-pill bg-primary';
                                            break;
                                        case 'processing':
                                            $statusClass = 'badge rounded-pill bg-warning';
                                            break;
                                        case 'complete':
                                            $statusClass = 'badge rounded-pill bg-success';
                                            break;
                                        default:
                                            $statusClass = 'badge rounded-pill bg-secondary';
                                            break;
                                    }
                                @endphp
                                <span style="font-size: 15px;" class="{{ $statusClass }}">
                                    {{ $booking->statusName }}
                                </span>
                            </td>
                            <td>{{ display_datetime($booking->created_at) }}</td>
                            <td style="text-align: center;">
                                @php
                                    $confirmType = $booking->confirm_type ?: 'pending';
                                    $confirmTypeLabels = [
                                        'confirmed' => __('Confirmed'),
                                        'not_confirmed' => __('Not Confirmed'),
                                        'pending' => __('Pending'),
                                        'cancelled' => __('Cancelled')
                                    ];

                                    // Bootstrap badge classes for confirm status
                                    $confirmBadgeClasses = [
                                        'confirmed' => 'badge rounded-pill bg-success',
                                        'not_confirmed' => 'badge rounded-pill bg-danger',
                                        'pending' => 'badge rounded-pill bg-warning',
                                        'cancelled' => 'badge rounded-pill bg-danger'
                                    ];
                                    $confirmBadgeClass = $confirmBadgeClasses[$confirmType] ?? 'badge rounded-pill bg-warning';
                                @endphp
                                <div style="display: flex; flex-direction: column; gap: 4px; align-items: center;">
                                    <span class="{{ $confirmBadgeClass }}" style="font-size: 15px;"
                                        title="{{ $confirmTypeLabels[$confirmType] ?? __('Pending') }}">
                                        {{ $confirmTypeLabels[$confirmType] ?? __('Pending') }}
                                    </span>
                                    @if($booking->confirmation_method && $confirmType === 'confirmed')
                                        <span
                                            style="display: inline-flex; align-items: center; gap: 4px; font-size: 11px; color: #6b7280; margin-top: 2px;">
                                            @if($booking->confirmation_method === 'whatsapp')
                                                <i class="fab fa-whatsapp" style="color: #25d366;"></i>
                                                {{ __('via WhatsApp') }}
                                            @elseif($booking->confirmation_method === 'email')
                                                <i class="fa fa-envelope" style="color: #3b82f6;"></i>
                                                {{ __('via Email') }}
                                            @endif
                                        </span>
                                    @endif
                                </div>
                            </td>




                            <td>
                                @if($booking->salesman_id && $booking->salesman_id > 0)
                                    @php
                                        $salesman = \App\User::find($booking->salesman_id);
                                    @endphp
                                    @if($salesman)
                                        <span style="display: inline-flex; align-items: center; gap: 5px; font-size: 13px;"
                                            title="{{ __('Salesman') }}: {{ $salesman->first_name }} {{ $salesman->last_name }}">
                                            <i class="fa fa-user-tie" style="opacity: 0.6;"></i>
                                            {{ $salesman->first_name }} {{ $salesman->last_name }}
                                        </span>
                                    @else
                                        <span style="color: var(--sales-text-secondary); font-size: 12px; font-style: italic;">
                                            {{ __('Not Assigned') }}
                                        </span>
                                    @endif
                                @else
                                    <span style="color: var(--sales-text-secondary); font-size: 12px; font-style: italic;">
                                        {{ __('Not Assigned') }}
                                    </span>
                                @endif
                            </td>
                            <td>
                                <div class="user-info">
                                    @if($booking->customer)
                                        <a href="{{ route('user.admin.detail', ['id' => $booking->customer_id]) }}"
                                            style="text-decoration: none;">
                                            @if($booking->customer->getAvatarUrl())
                                                <img src="{{ $booking->customer->getAvatarUrl() }}" class="user-avatar" alt="User">
                                            @else
                                                @php
                                                    $userName = $booking->first_name ?? $booking->email ?? 'U';
                                                    $firstLetter = strtoupper(mb_substr($userName, 0, 1));
                                                    // Generate color based on first letter
                                                    $colors = ['#6366f1', '#8b5cf6', '#ec4899', '#f59e0b', '#10b981', '#3b82f6', '#14b8a6', '#f97316'];
                                                    $colorIndex = ord($firstLetter) % count($colors);
                                                    $bgColor = $colors[$colorIndex];
                                                @endphp
                                                <div class="user-avatar"
                                                    style="background: {{ $bgColor }}; display: flex; align-items: center; justify-content: center; color: #ffffff; font-weight: 600; font-size: 16px;">
                                                    {{ $firstLetter }}
                                                </div>
                                            @endif
                                        </a>
                                    @else
                                        @php
                                            $userName = $booking->first_name ?? $booking->email ?? 'U';
                                            $firstLetter = strtoupper(mb_substr($userName, 0, 1));
                                            $colors = ['#6366f1', '#8b5cf6', '#ec4899', '#f59e0b', '#10b981', '#3b82f6', '#14b8a6', '#f97316'];
                                            $colorIndex = ord($firstLetter) % count($colors);
                                            $bgColor = $colors[$colorIndex];
                                        @endphp
                                        <div class="user-avatar"
                                            style="background: {{ $bgColor }}; display: flex; align-items: center; justify-content: center; color: #ffffff; font-weight: 600; font-size: 16px;">
                                            {{ $firstLetter }}
                                        </div>
                                    @endif
                                    <div class="user-details">
                                        @if($booking->customer)
                                            <a href="{{ route('user.admin.detail', ['id' => $booking->customer_id]) }}"
                                                style="color: var(--sales-text-primary); text-decoration: none; font-weight: 500; font-size: 14px;">
                                                {{ $booking->first_name }} {{ $booking->last_name }}
                                            </a>
                                        @else
                                            <span style="font-weight: 500; font-size: 14px;">
                                                {{ $booking->first_name }} {{ $booking->last_name }}
                                            </span>
                                        @endif
                                        @if($booking->phone)
                                            <a href="{{ route('user.admin.detail', ['id' => $booking->customer_id]) }}"
                                                class="user-phone">
                                                {{ $booking->phone }}
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td style="text-align: center;">
                                <div class="note-icon-wrapper" onclick="openNotesModal({{ $booking->id }})"
                                    style="cursor: pointer;">
                                    <i class="fa fa-sticky-note note-icon"></i>
                                    @php
                                        $notesCount = $booking->notes()->count();
                                    @endphp
                                    @if($notesCount > 0)
                                        <span class="note-counter">{{ $notesCount }}</span>
                                    @endif
                                </div>
                            </td>
                        </tr>

                        <!-- Expandable Details Row -->
                        <tr class="detail-row" id="detail-{{ $booking->id }}">
                            <td colspan="10">
                                <div class="detail-content">
                                    @php
                                        // Get all bookings with same payment_id (cart items)
                                        $relatedBookings = \Modules\Booking\Models\Booking::where('payment_id', $booking->payment_id)
                                            ->where('payment_id', '!=', null)
                                            ->orderBy('id')
                                            ->get();
                                    @endphp

                                    @if($relatedBookings->count() > 1)
                                        <!-- Cart Items Section -->
                                        <div class="detail-section" style="margin-bottom: 25px;">
                                            <h4>{{ __('Order Items') }} ({{ $relatedBookings->count() }} {{ __('Bookings') }})</h4>
                                            <div style="overflow-x: auto;">
                                                <table style="width: 100%; border-collapse: collapse;">
                                                    <thead>
                                                        <tr style="border-bottom: 2px solid var(--sales-border-color);">
                                                            <th style="padding: 10px; text-align: left; font-size: 13px;">
                                                                {{ __('Activity') }}
                                                            </th>
                                                            <th style="padding: 10px; text-align: left; font-size: 13px;">
                                                                {{ __('Guests') }}
                                                            </th>
                                                            <th style="padding: 10px; text-align: left; font-size: 13px;">
                                                                {{ __('Amount') }}
                                                            </th>

                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($relatedBookings as $relBooking)
                                                            <tr style="border-bottom: 1px solid var(--sales-border-color);">
                                                                <td style="padding: 12px;">
                                                                    @if($relService = $relBooking->service)
                                                                        <strong style="font-size: 14px;">{{ $relService->title }}</strong>
                                                                        @if($relBooking->start_date)
                                                                            <div
                                                                                style="font-size: 12px; color: var(--sales-text-secondary); margin-top: 4px;">
                                                                                <i class="fa fa-calendar"></i>
                                                                                {{ display_date($relBooking->start_date) }}
                                                                            </div>
                                                                        @endif
                                                                    @else
                                                                        <span
                                                                            style="color: var(--sales-text-secondary);">{{ __('[Deleted Service]') }}</span>
                                                                    @endif
                                                                </td>
                                                                <td style="padding: 12px;">
                                                                    @php
                                                                        $personTypes = $relBooking->getMeta('person_types');
                                                                        if ($personTypes) {
                                                                            $personTypes = json_decode($personTypes, true);
                                                                        }
                                                                    @endphp
                                                                    @if(!empty($personTypes) && is_array($personTypes))
                                                                        @foreach($personTypes as $type)
                                                                            <div style="font-size: 13px; margin-bottom: 3px;">
                                                                                <i class="fa fa-user"></i> {{ $type['number'] ?? 0 }} ×
                                                                                {{ $type['name'] ?? 'Guest' }}
                                                                            </div>
                                                                        @endforeach
                                                                    @else
                                                                        <div style="font-size: 13px;">
                                                                            <i class="fa fa-users"></i> {{ $relBooking->total_guests ?? 1 }}
                                                                            {{ __('Guests') }}
                                                                        </div>
                                                                    @endif
                                                                </td>
                                                                <td style="padding: 12px;">
                                                                    <strong style="font-size: 15px;">{{ $relBooking->total }}</strong>
                                                                    <span
                                                                        style="opacity: 0.8; font-size: 13px;">{!! get_current_currency_svg() !!}</span>
                                                                </td>

                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                    <tfoot>
                                                        <tr
                                                            style="border-top: 2px solid var(--sales-border-color); font-weight: bold;">
                                                            <td colspan="2" style="padding: 12px; text-align: right;">
                                                                {{ __('Total') }}:
                                                            </td>
                                                            <td style="padding: 12px;">
                                                                <strong
                                                                    style="font-size: 16px;">{{ $relatedBookings->sum('total') }}</strong>
                                                                <span
                                                                    style="opacity: 0.8; font-size: 14px;">{!! get_current_currency_svg() !!}</span>
                                                            </td>
                                                            <td></td>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>
                                    @endif



                                    <!-- Actions -->
                                    @if($booking_update)
                                        <div style="margin-top: 20px; display: flex; gap: 10px; flex-wrap: wrap;">
                                            @if(($booking->confirm_type ?: 'pending') === 'pending')
                                                <button class="btn-action btn-info" onclick="confirmOrderModal({{ $booking->id }})">
                                                    <i class="fa fa-check-circle"></i> {{ __('Confirm Order') }}
                                                </button>
                                                @if($booking->status === 'processing')
                                                    <button class="btn-action btn-danger" onclick="cancelOrder({{ $booking->id }})">
                                                        <i class="fa fa-times-circle"></i> {{ __('Cancel Order') }}
                                                    </button>
                                                @endif
                                            @else
                                                <button class="btn-action btn-warning" onclick="makePendingOrder({{ $booking->id }})">
                                                    <i class="fa fa-clock-o"></i> {{ __('Make Pending') }}
                                                </button>
                                                @if($booking->confirmation_method)
                                                    <small style="color: #6b7280; font-size: 11px; margin-left: 5px;">
                                                        {{ __('Confirmed via') }}
                                                        @if($booking->confirmation_method === 'whatsapp')
                                                            <i class="fab fa-whatsapp" style="color: #25d366;"></i> {{ __('WhatsApp') }}
                                                        @elseif($booking->confirmation_method === 'email')
                                                            <i class="fa fa-envelope" style="color: #3b82f6;"></i> {{ __('Email') }}
                                                        @endif
                                                    </small>
                                                @endif
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" style="text-align: center; padding: 40px; color: var(--sales-text-secondary);">
                                <i class="fa fa-inbox" style="font-size: 48px; margin-bottom: 15px;"></i>
                                <p>{{ __('No bookings found') }}</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="sales-pagination">
            @if(!empty($rows) && is_object($rows) && method_exists($rows, 'links'))
                {{ $rows->appends(request()->query())->links() }}
            @endif
        </div>
    </div>

    <!-- Confirm Order Modal -->
    <div class="modal fade" id="confirmOrderModal" tabindex="-1" role="dialog" aria-labelledby="confirmOrderModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmOrderModalLabel">
                        <i class="fa fa-check-circle"></i> {{ __('Confirm Order') }}
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="{{ __('Close') }}">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Step 1: Confirm Order -->
                    <div id="confirmStep1" class="confirm-step active">
                        <div class="confirm-warning">
                            <div class="warning-icon">
                                <i class="fa fa-exclamation-triangle"></i>
                            </div>
                            <div class="warning-content">
                                <h4>{{ __('Do you want to confirm this order now?') }}</h4>
                                <p>{{ __('If you proceed:') }}</p>
                                <ul class="confirm-actions-list">
                                    <li><i class="fa fa-check"></i> {{ __('The order status will change to Confirmed') }}
                                    </li>
                                    <li><i class="fa fa-user-tie"></i> {{ __('You’ll be set as the salesperson') }}</li>
                                    <li><i class="fa fa-bell"></i>
                                        {{ __(' the system will save a confirmation note for reference.') }}</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Step 2: Ticket Question -->
                    <div id="confirmStep2" class="confirm-step">
                        <div class="text-center">
                            <i class="fa fa-ticket" style="font-size: 48px; color: #3b82f6; margin-bottom: 20px;"></i>
                            <h4>{{ __('Should this order have a ticket?') }}</h4>
                            <p style="color: var(--sales-text-secondary); margin-bottom: 30px;">
                                {{ __('Tickets are required for some activities.') }}
                            </p>
                            <div class="ticket-options">
                                <button type="button" class="btn btn-primary btn-lg" onclick="selectTicketOption(true)"
                                    style="margin-right: 15px;">
                                    <i class="fa fa-check"></i> {{ __('Yes, ticket included') }}
                                </button>
                                <button type="button" class="btn btn-secondary btn-lg" onclick="selectTicketOption(false)">
                                    <i class="fa fa-times"></i> {{ __('No ticket') }}
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Step 3: Upload Ticket -->
                    <div id="confirmStep3" class="confirm-step">
                        <div class="text-center">
                            {{-- <i class="fa fa-cloud-upload"
                                style="font-size: 48px; color: #10b981; margin-bottom: 20px;"></i> --}}
                            {{-- <h4>{{ __('Upload Ticket') }}</h4> --}}
                            {{-- <p style="color: var(--sales-text-secondary); margin-bottom: 30px;"> --}}
                                {{-- {{ __('Please upload the ticket file (Image or PDF)') }} --}}
                                {{-- </p> --}}

                            <div class="upload-area" id="ticketUploadArea"
                                onclick="document.getElementById('ticketFileInput').click()">
                                <i class="fa fa-cloud-upload upload-icon"></i>
                                <p class="upload-text">{{ __('Click to upload ticket') }}</p>
                                <small class="upload-hint">{{ __('Supported formats: JPG, PNG, PDF (Max: 10MB)') }}</small>
                            </div>

                            <input type="file" id="ticketFileInput" accept="image/*,.pdf" style="display: none;">

                            <div id="ticketPreview" class="ticket-preview" style="display: none;">
                                <div class="preview-item">
                                    <div class="preview-thumbnail" id="ticketThumbnail"></div>
                                    <div class="preview-info">
                                        <div class="preview-name" id="ticketFileName"></div>
                                        <div class="preview-size" id="ticketFileSize"></div>
                                    </div>
                                    <button type="button" class="preview-remove" onclick="removeTicketFile()">
                                        <i class="fa fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 4: Send Method -->
                    <div id="confirmStep4" class="confirm-step">
                        <div class="text-center">
                            <i class="fa fa-paper-plane" style="font-size: 48px; color: #f59e0b; margin-bottom: 20px;"></i>
                            <h4>{{ __('How would you like to send the details to the customer?') }}</h4>
                            <p style="color: var(--sales-text-secondary); margin-bottom: 30px;">
                                {{ __('Choose the best way to contact your customer') }}
                            </p>
                            <div class="send-options">
                                <div class="send-option" onclick="selectSendMethod('whatsapp')">
                                    <div class="send-option-icon whatsapp">
                                        <i class="fa fa-whatsapp"></i>
                                    </div>
                                    <div class="send-option-content">
                                        <h5>{{ __('WhatsApp') }}</h5>
                                        <p>{{ __('Send via WhatsApp message') }}</p>
                                        <div class="customer-info" id="whatsappInfo">
                                            <i class="fa fa-phone"></i>
                                            <span id="customerPhone"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="send-option" onclick="selectSendMethod('email')">
                                    <div class="send-option-icon email">
                                        <i class="fa fa-envelope"></i>
                                    </div>
                                    <div class="send-option-content">
                                        <h5>{{ __('Email') }}</h5>
                                        <p>{{ __('Send via email with attachment') }}</p>
                                        <div class="customer-info" id="emailInfo">
                                            <i class="fa fa-at"></i>
                                            <span id="customerEmail"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 5: Final Confirmation -->
                    <div id="confirmStep5" class="confirm-step">
                        <div class="text-center">
                            <i class="fa fa-check-circle" style="font-size: 48px; color: #10b981; margin-bottom: 20px;"></i>
                            <h4>{{ __('Ready to confirm?') }}</h4>
                            <div class="final-summary" id="finalSummary">
                                <!-- Summary will be populated by JavaScript -->
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="previousStep()" id="prevBtn"
                        style="display: none;">
                        <i class="fa fa-arrow-left"></i> {{ __('Previous') }}
                    </button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        {{ __('Cancel') }}
                    </button>
                    <button type="button" class="btn btn-primary" onclick="nextStep()" id="nextBtn">
                        {{ __('Next') }} <i class="fa fa-arrow-right"></i>
                    </button>
                    <button type="button" class="btn btn-success" onclick="finalConfirmOrder()" id="confirmOrderBtn"
                        style="display: none;">
                        <i class="fa fa-check-circle"></i> {{ __('Confirm & Send') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Set Paid Modals -->
    @if(!empty($rows) && method_exists($rows, 'items'))
        @foreach($rows->items() as $booking)
            @if($service = $booking->service)
                @if(!empty($service->set_paid_modal_file) && view()->exists($service->set_paid_modal_file))
                    @include($service->set_paid_modal_file)
                @endif
            @endif
        @endforeach
    @endif

    <!-- Bulk Edit Form (Hidden) -->
    @if(!empty($booking_update))
        <form method="post" action="{{route('report.admin.booking.bulkEdit')}}" id="bulk-edit-form" style="display: none;">
            @csrf
            <input type="hidden" name="action" id="bulk-action">
            <input type="hidden" name="ids[]" id="bulk-ids">
        </form>
    @endif

    <script>
        // Global Variables
        let currentBookingId = null;
        let currentCancelBookingId = null;
        let mentionUsers = [];

        // Toggle expandable details
        function toggleDetails(bookingId) {
            const detailRow = document.getElementById('detail-' + bookingId);
            const icon = document.querySelector(`[data-booking-id="${bookingId}"] .expand-icon`);

            if (detailRow.classList.contains('show')) {
                detailRow.classList.remove('show');
                icon.classList.remove('expanded');
            } else {
                // Close all other details
                document.querySelectorAll('.detail-row.show').forEach(row => {
                    row.classList.remove('show');
                });
                document.querySelectorAll('.expand-icon.expanded').forEach(i => {
                    i.classList.remove('expanded');
                });

                // Check if data is already loaded
                if (!detailRow.hasAttribute('data-loaded')) {
                    // Load booking details via AJAX
                    loadBookingDetails(bookingId, detailRow);
                }

                // Open this detail
                detailRow.classList.add('show');
                icon.classList.add('expanded');
            }
        }

        // Load booking details via AJAX
        function loadBookingDetails(bookingId, detailRow) {
            // Show loading state
            detailRow.querySelector('.detail-content').innerHTML = `
                    <div style="text-align: center; padding: 40px;">
                        <i class="fa fa-spinner fa-spin" style="font-size: 24px; color: var(--sales-text-secondary);"></i>
                        <p style="color: var(--sales-text-secondary); margin-top: 10px;">{{ __('Loading booking details...') }}</p>
                    </div>
                `;

            $.ajax({
                url: '{{ route("report.admin.booking.get-details") }}',
                method: 'GET',
                data: {
                    booking_id: bookingId
                },
                success: function (response) {
                    if (response.success) {
                        renderBookingDetails(detailRow, response.data);
                        detailRow.setAttribute('data-loaded', 'true');
                    } else {
                        detailRow.querySelector('.detail-content').innerHTML = `
                                <div style="text-align: center; padding: 40px; color: var(--sales-text-secondary);">
                                    <i class="fa fa-exclamation-triangle" style="font-size: 24px;"></i>
                                    <p>{{ __('Failed to load booking details') }}</p>
                                </div>
                            `;
                    }
                },
                error: function (xhr) {
                    detailRow.querySelector('.detail-content').innerHTML = `
                            <div style="text-align: center; padding: 40px; color: var(--sales-text-secondary);">
                                <i class="fa fa-exclamation-triangle" style="font-size: 24px;"></i>
                                <p>{{ __('Error loading booking details') }}</p>
                            </div>
                        `;
                }
            });
        }

        // Render booking details HTML
        function renderBookingDetails(detailRow, data) {
            let html = '';

            // Cart Items Section (show all bookings in the order)
            if (data.cart_items && data.cart_items.length > 0) {
                html += `
                        <div class="detail-section" style="margin-bottom: 25px;">
                            <h4>{{ __('Order Items') }} (${data.cart_items.length} {{ __('Booking') }})</h4>
                            <div style="overflow-x: auto;">
                                <table style="width: 100%; border-collapse: collapse;">
                                    <thead>
                                        <tr style="border-bottom: 2px solid var(--sales-border-color);">
                                            <th style="padding: 10px; text-align: left; font-size: 13px;">
                                                {{ __('Activity') }}
                                            </th>
                                            <th style="padding: 10px; text-align: left; font-size: 13px;">
                                                {{ __('Date') }}
                                            </th>
                                            <th style="padding: 10px; text-align: left; font-size: 13px;">
                                                {{ __('Guests') }}
                                            </th>
                                            <th style="padding: 10px; text-align: left; font-size: 13px;">
                                                {{ __('Amount') }}
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                    `;

                data.cart_items.forEach(item => {
                    html += `
                            <tr style="border-bottom: 1px solid var(--sales-border-color);">
                                <td style="padding: 12px;">
                                    <strong style="font-size: 14px;">${item.service_title || '{{ __("N/A") }}'}</strong>
                                </td>
                                <td style="padding: 12px;">
                                    <div style="font-size: 13px;">
                                        <i class="fa fa-calendar"></i> ${item.start_date || '{{ __("N/A") }}'}
                                    </div>
                                </td>
                                <td style="padding: 12px;">
                                    ${item.person_types_html || `<div style="font-size: 13px;"><i class="fa fa-users"></i> ${item.total_guests || 1} {{ __('Guest(s)') }}</div>`}
                                </td>
                                <td style="padding: 12px;">
                                    <strong style="font-size: 15px;">${item.total}</strong>
                                    <span style="opacity: 0.8; font-size: 13px;">{!! get_current_currency_svg() !!}</span>
                                </td>
                            </tr>
                        `;
                });

                html += `
                                    </tbody>
                                    <tfoot>
                                        <tr style="border-top: 2px solid var(--sales-border-color); font-weight: bold;">
                                            <td colspan="3" style="padding: 12px; text-align: right;">
                                                {{ __('Total') }}:
                                            </td>
                                            <td style="padding: 12px;">
                                                <strong style="font-size: 16px;">${data.cart_total}</strong>
                                                <span style="opacity: 0.8; font-size: 14px;">{!! get_current_currency_svg() !!}</span>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    `;
            }

            // Customer Information Section (only if no cart items or if explicitly needed)
            if (!data.cart_items || data.cart_items.length === 0) {
                if (data.customer) {
                    html += `
                            <div class="detail-section" style="margin-bottom: 25px;">
                                <h4>{{ __('Customer Information') }}</h4>
                                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                                    <div>
                                        <strong style="font-size: 14px;">{{ __('Name') }}:</strong>
                                        <span style="margin-left: 8px;">${data.customer.first_name} ${data.customer.last_name}</span>
                                    </div>
                                    <div>
                                        <strong style="font-size: 14px;">{{ __('Email') }}:</strong>
                                        <span style="margin-left: 8px;">${data.customer.email || '{{ __("N/A") }}'}</span>
                                    </div>
                                    <div>
                                        <strong style="font-size: 14px;">{{ __('Phone') }}:</strong>
                                        <span style="margin-left: 8px;">${data.customer.phone || '{{ __("N/A") }}'}</span>
                                    </div>
                                    <div>
                                        <strong style="font-size: 14px;">{{ __('Country') }}:</strong>
                                        <span style="margin-left: 8px;">${data.customer.country || '{{ __("N/A") }}'}</span>
                                    </div>
                                </div>
                            </div>
                        `;
                }
            }

          


            // Payment Information Section
            if (data.payment) {
                html += `
                        <div class="detail-section" style="margin-bottom: 25px;">
                            <h4>{{ __('Payment Information') }}</h4>
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                                <div>
                                    <strong style="font-size: 14px;">{{ __('Payment Method') }}:</strong>
                                    <span style="margin-left: 8px;">${data.payment.gateway || '{{ __("N/A") }}'}</span>
                                </div>
                                <div>
                                    <strong style="font-size: 14px;">{{ __('Payment Status') }}:</strong>
                                    <span style="margin-left: 8px;">${data.payment.status || '{{ __("N/A") }}'}</span>
                                </div>
                                <div>
                                    <strong style="font-size: 14px;">{{ __('Transaction ID') }}:</strong>
                                    <span style="margin-left: 8px;">${data.payment.transaction_id || '{{ __("N/A") }}'}</span>
                                </div>
                                <div>
                                    <strong style="font-size: 14px;">{{ __('Payment Date') }}:</strong>
                                    <span style="margin-left: 8px;">${data.payment.created_at || '{{ __("N/A") }}'}</span>
                                </div>
                            </div>
                        </div>
                    `;
            }

            // Actions
            @if($booking_update)
                html += `
                            <div style="margin-top: 20px; display: flex; gap: 10px; flex-wrap: wrap;">
                        `;

                if (data.booking.confirm_type === 'pending') {
                    html += `
                                <button class="btn-action btn-info" onclick="confirmOrderModal(${data.booking.id})">
                                    <i class="fa fa-check-circle"></i> {{ __('Confirm Order') }}
                                </button>
                            `;

                    if (data.booking.status === 'processing') {
                        html += `
                                    <button class="btn-action btn-danger" onclick="cancelOrder(${data.booking.id})">
                                        <i class="fa fa-times-circle"></i> {{ __('Cancel Order') }}
                                    </button>
                                `;
                    }
                } else {
                    html += `
                                <button class="btn-action btn-warning" onclick="makePendingOrder(${data.booking.id})">
                                    <i class="fa fa-clock-o"></i> {{ __('Make Pending') }}
                                </button>
                            `;

                    if (data.booking.confirmation_method) {
                        html += `
                                    <small style="color: #6b7280; font-size: 11px; margin-left: 5px;">
                                        {{ __('Confirmed via') }}
                                        ${data.booking.confirmation_method === 'whatsapp' ? '<i class="fab fa-whatsapp" style="color: #25d366;"></i> {{ __("WhatsApp") }}' : '<i class="fa fa-envelope" style="color: #3b82f6;"></i> {{ __("Email") }}'}
                                    </small>
                                `;
                    }
                }

                html += `</div>`;
            @endif

            detailRow.querySelector('.detail-content').innerHTML = html;
        }

        // Modal functions
        function setPaidModal(bookingId) {
            $('#modal-paid-' + bookingId).modal('show');
        }

        // Confirm Order Modal
        function confirmOrderModal(bookingId) {
            // Reset modal to first step
            resetConfirmModal();

            // Get booking data
            const bookingRow = document.querySelector(`[data-booking-id="${bookingId}"]`);
            const orderNumber = bookingRow.querySelector('.order-link').textContent.trim();

            // Get customer info from the row
            const userDetails = bookingRow.querySelector('.user-details');
            const customerName = userDetails.querySelector('a, span').textContent.trim();
            const phoneElement = userDetails.querySelector('.user-phone');
            const customerPhone = phoneElement ? phoneElement.textContent.trim() : '';

            // Store booking data
            window.currentConfirmBooking = {
                id: bookingId,
                orderNumber: orderNumber,
                customerName: customerName,
                customerPhone: customerPhone,
                customerEmail: '', // Will be fetched from server if needed
                hasTicket: false,
                ticketFile: null,
                sendMethod: null
            };

            // Update modal title
            document.getElementById('confirmOrderModalLabel').innerHTML =
                `<i class="fa fa-check-circle"></i> ${orderNumber} - {{ __('Confirm Order') }}`;

            $('#confirmOrderModal').modal('show');
        }

        function resetConfirmModal() {
            // Hide all steps
            document.querySelectorAll('.confirm-step').forEach(step => {
                step.classList.remove('active');
            });

            // Show first step
            document.getElementById('confirmStep1').classList.add('active');

            // Reset buttons
            document.getElementById('prevBtn').style.display = 'none';
            document.getElementById('nextBtn').style.display = 'inline-block';
            document.getElementById('nextBtn').innerHTML = '{{ __("Next") }} <i class="fa fa-arrow-right"></i>';
            document.getElementById('confirmOrderBtn').style.display = 'none';

            // Clear file input
            document.getElementById('ticketFileInput').value = '';
            document.getElementById('ticketPreview').style.display = 'none';

            // Reset selections
            document.querySelectorAll('.send-option').forEach(option => {
                option.classList.remove('selected');
            });

            // Reset current step
            window.currentConfirmStep = 1;
        }

        let currentConfirmStep = 1;
        const totalSteps = 2; // Will be dynamic based on ticket selection

        function nextStep() {
            const currentStep = document.getElementById(`confirmStep${currentConfirmStep}`);
            let nextStepNumber = currentConfirmStep + 1;

            // Step 1 -> Step 2 (always)
            if (currentConfirmStep === 1) {
                nextStepNumber = 2;
            }
            // Step 2 -> Step 3 or 5 (based on ticket selection)
            else if (currentConfirmStep === 2) {
                // This will be handled by selectTicketOption function
                return;
            }
            // Step 3 -> Step 4 (if ticket uploaded)
            else if (currentConfirmStep === 3) {
                if (!window.currentConfirmBooking.ticketFile) {
                    alert('{{ __("Please upload a ticket file") }}');
                    return;
                }
                nextStepNumber = 4;
            }
            // Step 4 -> Step 5 (if send method selected)
            else if (currentConfirmStep === 4) {
                if (!window.currentConfirmBooking.sendMethod) {
                    alert('{{ __("Please select a send method") }}');
                    return;
                }
                nextStepNumber = 5;
            }

            showStep(nextStepNumber);
        }

        function previousStep() {
            let prevStepNumber = currentConfirmStep - 1;

            // Step 5 -> Step 4 or 2 (based on ticket)
            if (currentConfirmStep === 5) {
                prevStepNumber = window.currentConfirmBooking.hasTicket ? 4 : 2;
            }
            // Step 4 -> Step 3
            else if (currentConfirmStep === 4) {
                prevStepNumber = 3;
            }
            // Step 3 -> Step 2
            else if (currentConfirmStep === 3) {
                prevStepNumber = 2;
            }
            // Step 2 -> Step 1
            else if (currentConfirmStep === 2) {
                prevStepNumber = 1;
            }

            showStep(prevStepNumber);
        }

        function showStep(stepNumber) {
            // Hide current step
            document.getElementById(`confirmStep${currentConfirmStep}`).classList.remove('active');

            // Show new step
            document.getElementById(`confirmStep${stepNumber}`).classList.add('active');

            // Update current step
            currentConfirmStep = stepNumber;

            // Update buttons
            updateStepButtons();

            // Special actions for specific steps
            if (stepNumber === 4) {
                populateCustomerInfo();
            } else if (stepNumber === 5) {
                populateFinalSummary();
            }
        }

        function updateStepButtons() {
            const prevBtn = document.getElementById('prevBtn');
            const nextBtn = document.getElementById('nextBtn');
            const confirmBtn = document.getElementById('confirmOrderBtn');

            // Show/hide previous button
            prevBtn.style.display = currentConfirmStep > 1 ? 'inline-block' : 'none';

            // Show/hide next vs confirm button
            if (currentConfirmStep === 5) {
                nextBtn.style.display = 'none';
                confirmBtn.style.display = 'inline-block';
            } else {
                nextBtn.style.display = 'inline-block';
                confirmBtn.style.display = 'none';
            }
        }

        function selectTicketOption(hasTicket) {
            window.currentConfirmBooking.hasTicket = hasTicket;

            if (hasTicket) {
                showStep(3); // Go to upload step
            } else {
                showStep(4); // Skip to send method
            }
        }

        function selectSendMethod(method) {
            // Remove previous selection
            document.querySelectorAll('.send-option').forEach(option => {
                option.classList.remove('selected');
            });

            // Add selection to clicked option
            event.currentTarget.classList.add('selected');

            // Store selection
            window.currentConfirmBooking.sendMethod = method;

            // Auto advance to final step
            setTimeout(() => {
                showStep(5);
            }, 500);
        }

        function populateCustomerInfo() {
            const booking = window.currentConfirmBooking;

            // Show current info while loading from server
            document.getElementById('customerPhone').textContent = booking.customerPhone || '{{ __("Loading...") }}';
            document.getElementById('customerEmail').textContent = '{{ __("Loading...") }}';

            // Fetch complete customer info from server
            $.ajax({
                url: '{{ route("report.admin.booking.customer-info") }}',
                method: 'GET',
                data: {
                    booking_id: booking.id
                },
                success: function (response) {
                    if (response.success && response.customer) {
                        // Update stored data
                        booking.customerEmail = response.customer.email;
                        booking.customerPhone = response.customer.phone;

                        // Update display
                        document.getElementById('customerPhone').textContent =
                            response.customer.phone || '{{ __("Not available") }}';
                        document.getElementById('customerEmail').textContent =
                            response.customer.email || '{{ __("Not available") }}';
                    }
                },
                error: function () {
                    document.getElementById('customerPhone').textContent =
                        booking.customerPhone || '{{ __("Not available") }}';
                    document.getElementById('customerEmail').textContent = '{{ __("Error loading") }}';
                }
            });
        }

        function populateFinalSummary() {
            const booking = window.currentConfirmBooking;
            const summaryHtml = `
                                                                                        <div class="summary-item">
                                                                                            <span class="summary-label">{{ __("Order") }}:</span>
                                                                                            <span class="summary-value">${booking.orderNumber}</span>
                                                                                        </div>
                                                                                        <div class="summary-item">
                                                                                            <span class="summary-label">{{ __("Customer Name") }}:</span>
                                                                                            <span class="summary-value">${booking.customerName}</span>
                                                                                        </div>
                                                                                        <div class="summary-item">
                                                                                            <span class="summary-label">{{ __("Ticket Available") }}:</span>
                                                                                            <span class="summary-value">${booking.hasTicket ? '{{ __("Yes") }}' : '{{ __("No") }}'}</span>
                                                                                        </div>
                                                                                        ${booking.hasTicket ? `
                                                                                        <div class="summary-item">
                                                                                            <span class="summary-label">{{ __("Ticket File") }}:</span>
                                                                                            <span class="summary-value">${booking.ticketFile ? booking.ticketFile.name : '{{ __("No file") }}'}</span>
                                                                                        </div>
                                                                                        ` : ''}
                                                                                        <div class="summary-item">
                                                                                            <span class="summary-label">{{ __("Send Method") }}:</span>
                                                                                            <span class="summary-value">${booking.sendMethod === 'whatsapp' ? '{{ __("WhatsApp") }}' : '{{ __("Email") }}'}</span>
                                                                                        </div>
                                                                                    `;

            document.getElementById('finalSummary').innerHTML = summaryHtml;
        }

        // File upload handlers
        document.getElementById('ticketFileInput').addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (file) {
                handleTicketFile(file);
            }
        });

        function handleTicketFile(file) {
            // Validate file
            const maxSize = 10 * 1024 * 1024; // 10MB
            const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'application/pdf'];

            if (file.size > maxSize) {
                alert('{{ __("File size must be less than 10MB") }}');
                return;
            }

            if (!allowedTypes.includes(file.type)) {
                alert('{{ __("Only JPG, PNG and PDF files are allowed") }}');
                return;
            }

            // Store file
            window.currentConfirmBooking.ticketFile = file;

            // Show preview
            showTicketPreview(file);
        }

        function showTicketPreview(file) {
            const preview = document.getElementById('ticketPreview');
            const thumbnail = document.getElementById('ticketThumbnail');
            const fileName = document.getElementById('ticketFileName');
            const fileSize = document.getElementById('ticketFileSize');

            // Set file info
            fileName.textContent = file.name;
            fileSize.textContent = formatFileSize(file.size);

            // Set thumbnail
            if (file.type.startsWith('image/')) {
                const img = document.createElement('img');
                img.src = URL.createObjectURL(file);
                thumbnail.innerHTML = '';
                thumbnail.appendChild(img);
            } else if (file.type === 'application/pdf') {
                thumbnail.innerHTML = '<i class="fa fa-file-pdf-o" style="color: #ef4444; font-size: 24px;"></i>';
                thumbnail.style.background = 'rgba(239, 68, 68, 0.1)';
            }

            preview.style.display = 'block';
        }

        function removeTicketFile() {
            window.currentConfirmBooking.ticketFile = null;
            document.getElementById('ticketFileInput').value = '';
            document.getElementById('ticketPreview').style.display = 'none';
        }

        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }

        // Drag and drop for ticket upload
        const uploadArea = document.getElementById('ticketUploadArea');
        if (uploadArea) {
            uploadArea.addEventListener('dragover', function (e) {
                e.preventDefault();
                this.classList.add('dragover');
            });

            uploadArea.addEventListener('dragleave', function (e) {
                e.preventDefault();
                this.classList.remove('dragover');
            });

            uploadArea.addEventListener('drop', function (e) {
                e.preventDefault();
                this.classList.remove('dragover');
                const files = e.dataTransfer.files;
                if (files.length > 0) {
                    handleTicketFile(files[0]);
                }
            });
        }

        function finalConfirmOrder() {
            const booking = window.currentConfirmBooking;

            // Disable button and show loading
            $('#confirmOrderBtn').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> {{ __("Processing...") }}');

            // Prepare form data
            const formData = new FormData();
            formData.append('booking_id', booking.id);
            formData.append('has_ticket', booking.hasTicket ? '1' : '0');
            formData.append('send_method', booking.sendMethod);

            if (booking.hasTicket && booking.ticketFile) {
                formData.append('ticket_file', booking.ticketFile);
            }

            formData.append('_token', '{{ csrf_token() }}');

            showLoading();

            $.ajax({
                url: '{{ route("report.admin.booking.confirm-order") }}',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    hideLoading();
                    $('#confirmOrderModal').modal('hide');

                    if (response.success) {
                        if (response.whatsapp_url) {
                            // Open WhatsApp
                            window.open(response.whatsapp_url, '_blank');
                        }

                        alert(response.message || '{{ __("Order confirmed successfully") }}');
                        window.location.reload();
                    } else {
                        alert(response.message || '{{ __("Something went wrong") }}');
                    }
                },
                error: function (xhr) {
                    hideLoading();
                    $('#confirmOrderModal').modal('hide');
                    alert('Error: ' + (xhr.responseJSON?.message || '{{ __("Something went wrong") }}'));
                },
                complete: function () {
                    // Re-enable button
                    $('#confirmOrderBtn').prop('disabled', false).html('<i class="fa fa-check-circle"></i> {{ __("Confirm & Send") }}');
                }
            });
        }

        // Confirm Order Function (old - replaced by finalConfirmOrder)
        function confirmOrder(bookingId) {
            // Disable button and show loading
            $('#confirmOrderBtn').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> {{ __("Processing...") }}');

            showLoading();

            $.ajax({
                url: '{{ route("report.admin.booking.confirm-order") }}',
                method: 'POST',
                data: {
                    booking_id: bookingId,
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {
                    hideLoading();
                    $('#confirmOrderModal').modal('hide');

                    if (response.success) {
                        // Show success message
                        alert(response.message || '{{ __("Order confirmed successfully!") }}');
                        // Reload page to update status
                        window.location.reload();
                    } else {
                        alert(response.message || '{{ __("Failed to confirm order") }}');
                    }
                },
                error: function (xhr) {
                    hideLoading();
                    $('#confirmOrderModal').modal('hide');
                    alert('Error: ' + (xhr.responseJSON?.message || '{{ __("Something went wrong") }}'));
                },
                complete: function () {
                    // Re-enable button
                    $('#confirmOrderBtn').prop('disabled', false).html('<i class="fa fa-check-circle"></i> {{ __("Yes, Confirm Order") }}');
                }
            });
        }

        // Set Paid functionality
        $(document).on('click', '#set_paid_btn', function (e) {
            var id = $(this).data('id');
            $.ajax({
                url: '{{ url('/') }}/booking/setPaidAmount',
                data: {
                    id: id,
                    remain: $('#modal-paid-' + id + ' #set_paid_input').val(),
                },
                dataType: 'json',
                type: 'post',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function (res) {
                    alert(res.message);
                    window.location.reload();
                },
                error: function (xhr) {
                    alert('Error: ' + (xhr.responseJSON?.message || 'Something went wrong'));
                }
            });
        });

        // Show/Hide Loading
        function showLoading() {
            document.getElementById('loading-overlay').classList.add('show');
        }

        function hideLoading() {
            document.getElementById('loading-overlay').classList.remove('show');
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', function () {
            // Show loading on form submit
            document.getElementById('sales-filter-form').addEventListener('submit', function () {
                showLoading();
            });

            // Auto-hide loading after page load
            setTimeout(hideLoading, 500);
        });

        // Table sorting
        document.querySelectorAll('.sortable').forEach(th => {
            th.addEventListener('click', function () {
                const sortBy = this.dataset.sort;
                const currentUrl = new URL(window.location.href);
                const currentSort = currentUrl.searchParams.get('sort_by');
                const currentOrder = currentUrl.searchParams.get('sort_order') || 'desc';

                // Toggle order if same column
                const newOrder = (currentSort === sortBy && currentOrder === 'desc') ? 'asc' : 'desc';

                currentUrl.searchParams.set('sort_by', sortBy);
                currentUrl.searchParams.set('sort_order', newOrder);

                window.location.href = currentUrl.toString();
            });
        });

        // Notes Sidebar Functions
        function openNotesModal(bookingId) {
            currentBookingId = bookingId;

            // Get booking order number for display
            const orderNumber = $('[data-booking-id="' + bookingId + '"]').closest('tr').find('.order-link').text().trim();
            $('#orderNoteNumber').text(orderNumber);

            // Clear textarea
            $('#sidebarNoteTextarea').val('');

            // Open sidebar
            $('#notesSidebar').addClass('open');
            $('body').css('overflow', 'hidden');

            loadNotes(bookingId);

            // Focus on textarea
            setTimeout(() => {
                $('#sidebarNoteTextarea').focus();
            }, 400);
        }

        function closeNotesSidebar() {
            $('#notesSidebar').removeClass('open');
            $('body').css('overflow', '');
        }

        // Keyboard shortcut: Ctrl+Enter to submit note
        $(document).on('keydown', '#sidebarNoteTextarea', function (e) {
            if ((e.ctrlKey || e.metaKey) && e.keyCode === 13) {
                e.preventDefault();
                saveNoteSidebar();
            }
        });

        // Mention Functions
        function toggleMentionDropdown() {
            const dropdown = document.getElementById('mentionDropdown');
            if (dropdown.style.display === 'none' || dropdown.style.display === '') {
                // Load all users when opening dropdown
                loadMentionUsers('');
                dropdown.style.display = 'block';
                setTimeout(() => {
                    document.getElementById('mentionSearch').focus();
                }, 100);
            } else {
                dropdown.style.display = 'none';
                // Clear search input when closing
                document.getElementById('mentionSearch').value = '';
            }
        }

        function loadMentionUsers(searchTerm = '') {
            showLoading();

            $.ajax({
                url: '{{ route("user.admin.getForSelect2") }}',
                method: 'GET',
                data: {
                    exclude_roles: ['customer', 'vendor'], // Exclude customer and vendor roles
                    q: searchTerm // Search by name or phone
                },
                success: function (response) {
                    hideLoading();
                    if (response.results) {
                        mentionUsers = response.results;
                        displayMentionUsers(mentionUsers);
                    }
                },
                error: function () {
                    hideLoading();
                    $('#mentionUsersList').html('<div class="mention-empty"><i class="fa fa-exclamation-triangle"></i>{{ __("Failed to load users") }}</div>');
                }
            });
        }

        function displayMentionUsers(users) {
            if (!users || users.length === 0) {
                $('#mentionUsersList').html('<div class="mention-empty"><i class="fa fa-users"></i>{{ __("No users found") }}</div>');
                $('#mentionUsersCount').text('0 {{ __("users") }}');
                return;
            }

            // Update counter
            $('#mentionUsersCount').text(users.length + ' {{ __("users") }}');

            let html = '';
            users.forEach(user => {
                const userName = user.text || user.first_name || 'Unknown';
                const userRole = user.role_name || 'User';
                const userId = user.id;
                const userAvatar = user.avatar_url || null;
                const userPhone = user.phone || '';
                const userEmail = user.email || '';
                const firstLetter = userName.charAt(0).toUpperCase();
                const bgColor = getRandomColor();

                // Escape single quotes in username for onclick
                const safeUserName = userName.replace(/'/g, "\\'");

                const avatarHtml = userAvatar
                    ? `<img src="${userAvatar}" alt="${userName}">`
                    : `<div class="mention-user-avatar-letter" style="background: ${bgColor};">${firstLetter}</div>`;

                const phoneHtml = userPhone ? `<div class="mention-user-phone"><i class="fa fa-phone"></i> ${userPhone}</div>` : '';

                html += `
                                                                                                                    <div class="mention-user-item" onclick="insertMention('${safeUserName}', ${userId})" data-user-id="${userId}">
                                                                                                                        <div class="mention-user-avatar">
                                                                                                                            ${avatarHtml}
                                                                                                                        </div>
                                                                                                                        <div class="mention-user-info">
                                                                                                                            <div class="mention-user-name">${userName}</div>
                                                                                                                            ${phoneHtml}
                                                                                                                            <div class="mention-user-role"><i class="fa fa-user-tag"></i> ${userRole}</div>
                                                                                                                        </div>
                                                                                                                    </div>
                                                                                                                `;
            });

            $('#mentionUsersList').html(html);
        } function filterMentionUsers() {
            const searchTerm = $('#mentionSearch').val().trim();
            // Load users from server with search term
            loadMentionUsers(searchTerm);
        }

        function insertMention(userName, userId) {
            const textarea = document.getElementById('sidebarNoteTextarea');
            const mention = `@${userName} `;

            // Get current cursor position
            const cursorPos = textarea.selectionStart;
            const textBefore = textarea.value.substring(0, cursorPos);
            const textAfter = textarea.value.substring(cursorPos);

            // Insert mention at cursor position
            textarea.value = textBefore + mention + textAfter;

            // Set cursor position after mention
            const newPos = cursorPos + mention.length;
            textarea.setSelectionRange(newPos, newPos);
            textarea.focus();

            // Close dropdown
            document.getElementById('mentionDropdown').style.display = 'none';
        }

        // Close mention dropdown when clicking outside
        $(document).on('click', function (e) {
            const dropdown = document.getElementById('mentionDropdown');
            const button = $('.btn-mention')[0];

            if (dropdown && dropdown.style.display !== 'none') {
                if (!dropdown.contains(e.target) && e.target !== button && !button.contains(e.target)) {
                    dropdown.style.display = 'none';
                }
            }
        });

        function loadNotes(bookingId) {
            showLoading();

            $.ajax({
                url: '{{ route("report.admin.booking.get-notes") }}',
                method: 'GET',
                data: {
                    booking_id: bookingId
                },
                success: function (response) {
                    hideLoading();
                    if (response.success) {
                        displayNotes(response.notes);
                    } else {
                        $('#notesContent').html('<div class="empty-notes"><i class="fa fa-exclamation-circle"></i><p>' + (response.message || '{{ __("Failed to load notes") }}') + '</p></div>');
                    }
                },
                error: function () {
                    hideLoading();
                    $('#notesContent').html('<div class="empty-notes"><i class="fa fa-exclamation-circle"></i><p>{{ __("An error occurred while loading notes") }}</p></div>');
                }
            });
        }

        function displayNotes(notes) {
            if (!notes || notes.length === 0) {
                $('#notesContent').html(`
                                                                                                                                    <div class="empty-notes">
                                                                                                                                        <i class="fa fa-sticky-note"></i>
                                                                                                                                        <p>{{ __("No Notes Yet!") }}</p>
                                                                                                                                    </div>
                                                                                                                                `);
                return;
            }

            let html = '<div class="notes-timeline">';
            notes.forEach(note => {
                const avatarContent = note.user_avatar
                    ? `<img src="${note.user_avatar}" alt="${note.user_name}">`
                    : `<div class="note-avatar-letter" style="background: ${getRandomColor()};">${note.user_name.charAt(0).toUpperCase()}</div>`;

                // Build attachments HTML
                let attachmentsHtml = '';
                if (note.attachments && note.attachments.length > 0) {
                    attachmentsHtml = '<div class="note-attachments">';
                    note.attachments.forEach(attachment => {
                        const ext = attachment.extension.toLowerCase();
                        const fileSize = formatFileSize(attachment.size);
                        const fileName = attachment.original_name;

                        let thumbnailHtml = '';

                        // Check if image
                        if (['jpg', 'jpeg', 'png', 'gif'].includes(ext)) {
                            thumbnailHtml = `<img src="${attachment.url}" alt="${fileName}" class="attachment-thumbnail">`;
                        }
                        // PDF icon
                        else if (ext === 'pdf') {
                            thumbnailHtml = `<div class="attachment-icon-wrapper attachment-icon-pdf-wrapper"><i class="fa fa-file-pdf"></i></div>`;
                        }
                        // Document icon
                        else if (['doc', 'docx'].includes(ext)) {
                            thumbnailHtml = `<div class="attachment-icon-wrapper attachment-icon-doc-wrapper"><i class="fa fa-file-word"></i></div>`;
                        }
                        // Text file icon
                        else if (ext === 'txt') {
                            thumbnailHtml = `<div class="attachment-icon-wrapper attachment-icon-text-wrapper"><i class="fa fa-file-alt"></i></div>`;
                        }
                        // Default file icon
                        else {
                            thumbnailHtml = `<div class="attachment-icon-wrapper attachment-icon-doc-wrapper"><i class="fa fa-file"></i></div>`;
                        }

                        attachmentsHtml += `
                                                                                                                <a href="${attachment.url}" class="note-attachment" download="${fileName}" target="_blank">
                                                                                                                    ${thumbnailHtml}
                                                                                                                    <div class="attachment-info">
                                                                                                                        <span class="attachment-name">${fileName}</span>
                                                                                                                        <span class="attachment-size">${fileSize}</span>
                                                                                                                    </div>
                                                                                                                </a>
                                                                                                            `;
                    });
                    attachmentsHtml += '</div>';
                }

                html += `
                                                                                                                                    <div class="note-item">
                                                                                                                                        <div class="note-avatar">
                                                                                                                                            ${avatarContent}
                                                                                                                                        </div>
                                                                                                                                        <div class="note-header">
                                                                                                                                            <span class="note-author">${note.user_name}</span>
                                                                                                                                            <span class="note-time">${note.created_at}</span>
                                                                                                                                        </div>
                                                                                                                                        <div class="note-content">${note.content}</div>
                                                                                                                                        ${attachmentsHtml}
                                                                                                                                    </div>
                                                                                                                                `;
            });
            html += '</div>';

            $('#notesContent').html(html);
        }

        function getRandomColor() {
            const colors = ['#6366f1', '#8b5cf6', '#ec4899', '#f59e0b', '#10b981', '#3b82f6', '#14b8a6', '#f97316'];
            return colors[Math.floor(Math.random() * colors.length)];
        }

        function openAddNoteFromView() {
            closeNotesSidebar();
            setTimeout(() => {
                openAddNoteModal(currentBookingId);
            }, 300);
        }

        // Handle file selection
        function handleFileSelect(event) {
            const files = event.target.files;
            const previewContainer = $('#attachmentPreview');
            previewContainer.empty();

            if (files.length > 0) {
                previewContainer.show();
                Array.from(files).forEach((file, index) => {
                    const ext = file.name.split('.').pop().toLowerCase();
                    const fileSize = formatFileSize(file.size);

                    const attachmentItem = $('<div class="attachment-item" data-index="' + index + '"></div>');

                    // Check if image to show thumbnail
                    if (['jpg', 'jpeg', 'png', 'gif'].includes(ext)) {
                        const reader = new FileReader();
                        reader.onload = function (e) {
                            const thumbnail = $('<img class="attachment-thumbnail" src="' + e.target.result + '" alt="' + file.name + '">');
                            attachmentItem.append(thumbnail);
                        };
                        reader.readAsDataURL(file);
                    }
                    // PDF icon
                    else if (ext === 'pdf') {
                        attachmentItem.append('<div class="attachment-icon-wrapper attachment-icon-pdf-wrapper"><i class="fa fa-file-pdf"></i></div>');
                    }
                    // Document icon
                    else if (['doc', 'docx'].includes(ext)) {
                        attachmentItem.append('<div class="attachment-icon-wrapper attachment-icon-doc-wrapper"><i class="fa fa-file-word"></i></div>');
                    }
                    // Text file icon
                    else if (ext === 'txt') {
                        attachmentItem.append('<div class="attachment-icon-wrapper attachment-icon-text-wrapper"><i class="fa fa-file-alt"></i></div>');
                    }
                    // Default file icon
                    else {
                        attachmentItem.append('<div class="attachment-icon-wrapper attachment-icon-doc-wrapper"><i class="fa fa-file"></i></div>');
                    }

                    const fileInfo = $(`
                                                                                                            <div class="attachment-info">
                                                                                                                <span class="attachment-name">${file.name}</span>
                                                                                                                <span class="attachment-size">${fileSize}</span>
                                                                                                            </div>
                                                                                                        `);

                    const removeBtn = $('<i class="fa fa-times attachment-item-remove" onclick="removeAttachment(' + index + ')"></i>');

                    attachmentItem.append(fileInfo);
                    attachmentItem.append(removeBtn);
                    previewContainer.append(attachmentItem);
                });
            } else {
                previewContainer.hide();
            }
        }

        // Remove attachment from preview
        function removeAttachment(index) {
            const fileInput = document.getElementById('noteAttachments');
            const dt = new DataTransfer();
            const files = fileInput.files;

            for (let i = 0; i < files.length; i++) {
                if (i !== index) {
                    dt.items.add(files[i]);
                }
            }

            fileInput.files = dt.files;
            handleFileSelect({ target: fileInput });
        }

        // Get file icon based on extension
        function getFileIcon(filename) {
            const ext = filename.split('.').pop().toLowerCase();
            if (['jpg', 'jpeg', 'png', 'gif'].includes(ext)) {
                return 'fa-file-image attachment-icon-image';
            } else if (ext === 'pdf') {
                return 'fa-file-pdf attachment-icon-pdf';
            } else if (['doc', 'docx'].includes(ext)) {
                return 'fa-file-word attachment-icon-doc';
            } else {
                return 'fa-file attachment-icon-doc';
            }
        }

        // Format file size
        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
        }

        // Save Note from Sidebar
        function saveNoteSidebar() {
            const noteText = $('#sidebarNoteTextarea').val().trim();

            if (!noteText) {
                alert('{{ __("Please enter a note") }}');
                return;
            }

            if (!currentBookingId) {
                alert('{{ __("Booking ID not found") }}');
                return;
            }

            showLoading();

            // Create FormData for file upload
            const formData = new FormData();
            formData.append('booking_id', currentBookingId);
            formData.append('note', noteText);
            formData.append('_token', '{{ csrf_token() }}');

            // Add attachments if any
            const fileInput = document.getElementById('noteAttachments');
            if (fileInput.files.length > 0) {
                Array.from(fileInput.files).forEach((file) => {
                    formData.append('attachments[]', file);
                });
            }

            $.ajax({
                url: '{{ route("report.admin.booking.add-note") }}',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    if (response.success) {
                        $('#sidebarNoteTextarea').val('');
                        fileInput.value = '';
                        $('#attachmentPreview').empty().hide();
                        hideLoading();
                        loadNotes(currentBookingId);
                    } else {
                        hideLoading();
                        alert(response.message || '{{ __("Failed to save note") }}');
                    }
                },
                error: function (xhr) {
                    hideLoading();
                    alert('Error: ' + (xhr.responseJSON?.message || '{{ __("Something went wrong") }}'));
                }
            });
        }

        // Add Note Modal & Functions
        function openAddNoteModal(bookingId) {
            currentBookingId = bookingId;
            $('#addNoteModal').data('booking-id', bookingId);
            $('#addNoteModal').modal('show');
            $('#noteTextarea').val('');
        }

        function saveNote() {
            const bookingId = $('#addNoteModal').data('booking-id');
            const noteText = $('#noteTextarea').val().trim();

            if (!noteText) {
                alert('{{ __("Please enter a note") }}');
                return;
            }

            showLoading();

            $.ajax({
                url: '{{ route("report.admin.booking.add-note") }}',
                method: 'POST',
                data: {
                    booking_id: bookingId,
                    note: noteText,
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {
                    if (response.success) {
                        $('#addNoteModal').modal('hide');
                        hideLoading();

                        // Open sidebar again and reload notes
                        setTimeout(() => {
                            openNotesModal(bookingId);
                        }, 300);
                    } else {
                        hideLoading();
                        alert(response.message || '{{ __("Failed to save note") }}');
                    }
                },
                error: function (xhr) {
                    hideLoading();
                    alert('Error: ' + (xhr.responseJSON?.message || '{{ __("Something went wrong") }}'));
                }
            });
        }

        // Make Order Pending Function
        function makePendingOrder(bookingId) {
            console.log('makePendingOrder called with ID:', bookingId); // Debug log
            if (!confirm('{{ __("Are you sure you want to make this order pending?") }}')) {
                return;
            }

            showLoading();

            $.ajax({
                url: '{{ route("report.admin.booking.make-pending") }}',
                method: 'POST',
                data: {
                    booking_id: bookingId,
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {
                    hideLoading();
                    if (response.success) {
                        alert(response.message || '{{ __("Order status changed to pending successfully") }}');
                        window.location.reload();
                    } else {
                        alert(response.message || '{{ __("Something went wrong") }}');
                    }
                },
                error: function (xhr) {
                    hideLoading();
                    alert('Error: ' + (xhr.responseJSON?.message || '{{ __("Something went wrong") }}'));
                }
            });
        }

        // Debug: Check if function is defined
        console.log('makePendingOrder function defined:', typeof makePendingOrder);

        // Cancel Order Function - Show Modal
        function cancelOrder(bookingId) {
            currentCancelBookingId = bookingId;
            $('#cancelOrderModal').modal('show');
        }

        // Execute Cancel Order after confirmation from modal
        function executeCancelOrder() {
            if (!currentCancelBookingId) return;

            // Disable button and show loading
            $('#confirmCancelBtn').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> {{ __("Processing...") }}');

            showLoading();

            $.ajax({
                url: '/admin/module/report/booking/cancel/' + currentCancelBookingId,
                type: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    hideLoading();
                    $('#cancelOrderModal').modal('hide');

                    if (response.success) {
                        showMessage(response.message || '{{ __("Order cancelled successfully") }}', 'success', function () {
                            location.reload();
                        });
                    } else {
                        showMessage(response.message || '{{ __("Something went wrong") }}', 'error');
                    }
                },
                error: function (xhr) {
                    hideLoading();
                    $('#cancelOrderModal').modal('hide');
                    showMessage(xhr.responseJSON?.message || '{{ __("Error cancelling order") }}', 'error');
                },
                complete: function () {
                    $('#confirmCancelBtn').prop('disabled', false).html('<i class="fa fa-times-circle"></i> {{ __("Yes, Cancel Order") }}');
                    currentCancelBookingId = null;
                }
            });
        }

        // Show message modal (success or error)
        function showMessage(message, type = 'success', callback = null) {
            const modal = $('#messageModal');
            const icon = $('#messageModalIcon');
            const titleText = $('#messageModalTitleText');
            const body = $('#messageModalBody');
            const okBtn = $('#messageModalOkBtn');

            if (type === 'success') {
                icon.attr('class', 'fa fa-check-circle').css('color', '#10b981');
                titleText.text('{{ __("Success") }}');
                okBtn.attr('class', 'btn btn-success');
            } else {
                icon.attr('class', 'fa fa-exclamation-circle').css('color', '#ef4444');
                titleText.text('{{ __("Error") }}');
                okBtn.attr('class', 'btn btn-danger');
            }

            body.text(message);

            // Handle callback on modal close
            if (callback) {
                modal.off('hidden.bs.modal').on('hidden.bs.modal', function () {
                    callback();
                });
            }

            modal.modal('show');
        }
    </script>

    <!-- Add Note Modal -->
    <div class="modal fade" id="addNoteModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content"
                style="background: var(--sales-bg-secondary); color: var(--sales-text-primary); border: 1px solid var(--sales-border-color);">
                <div class="modal-header" style="border-bottom: 1px solid var(--sales-border-color);">
                    <h5 class="modal-title">{{ __('Add Note') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                        style="color: var(--sales-text-primary);">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="noteTextarea">{{ __('Note') }}</label>
                        <textarea class="form-control" id="noteTextarea" rows="5"
                            placeholder="{{ __('Enter your note here...') }}"
                            style="background: var(--sales-bg-primary); color: var(--sales-text-primary); border: 1px solid var(--sales-border-color);">
                                                                                                                                                            </textarea>
                    </div>
                    <div class="form-group" style="margin-bottom: 0;">
                        <small class="text-muted" style="color: var(--sales-text-secondary) !important;">
                            <i class="fa fa-info-circle"></i> {{ __('This note will be visible to all staff members') }}
                        </small>
                    </div>
                </div>
                <div class="modal-footer" style="border-top: 1px solid var(--sales-border-color);">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Cancel') }}</button>
                    <button type="button" class="btn btn-primary" onclick="saveNote()"
                        style="background: #6366f1; border-color: #6366f1;">
                        <i class="fa fa-save"></i> {{ __('Save Note') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Notes Sidebar -->
    <div id="notesSidebar" class="notes-sidebar">
        <div class="notes-sidebar-overlay" onclick="closeNotesSidebar()"></div>
        <div class="notes-sidebar-content">
            <div class="notes-sidebar-header">
                <div>
                    <h5 class="notes-sidebar-title">
                        <i class="fa fa-sticky-note"></i> {{ __('Order Note:') }} <span id="orderNoteNumber"></span>
                    </h5>
                </div>
                <button type="button" class="notes-close-btn" onclick="closeNotesSidebar()">
                    <i class="fa fa-times"></i>
                </button>
            </div>

            <!-- Add Note Section -->
            <div class="notes-add-section">
                <div class="form-group" style="margin-bottom: 12px;">
                    <label for="sidebarNoteTextarea"
                        style="color: var(--sales-text-primary); font-weight: 500; font-size: 14px; margin-bottom: 8px; display: block;">
                        {{ __('Note') }}
                    </label>
                    <textarea class="form-control sidebar-note-input" id="sidebarNoteTextarea" rows="3"
                        placeholder="{{ __('Enter your note here...') }}"></textarea>
                </div>

                <!-- Attachment Preview -->
                <div id="attachmentPreview" class="attachment-preview" style="display: none;"></div>

                <div style="display: flex; gap: 10px; align-items: center; position: relative;">
                    <button type="button" class="btn-mention" onclick="toggleMentionDropdown()">
                        <i class="fa fa-at"></i> {{ __('Mention') }}
                    </button>

                    <!-- Mention Dropdown -->
                    <div id="mentionDropdown" class="mention-dropdown" style="display: none;">
                        <div class="mention-dropdown-header">
                            <div
                                style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                                <span style="font-size: 12px; color: var(--sales-text-secondary); font-weight: 600;">
                                    <i class="fa fa-users"></i> {{ __('Select User') }}
                                </span>
                                <span id="mentionUsersCount"
                                    style="font-size: 11px; color: var(--sales-text-secondary);"></span>
                            </div>
                            <input type="text" id="mentionSearch" class="mention-search"
                                placeholder="{{ __('Search by name or phone...') }}" onkeyup="filterMentionUsers()">
                        </div>
                        <div class="mention-dropdown-body" id="mentionUsersList">
                            <!-- Users will be loaded here -->
                        </div>
                    </div>

                    <input type="file" id="noteAttachments" multiple accept=".jpg,.jpeg,.png,.gif,.pdf,.doc,.docx,.txt"
                        style="display: none;" onchange="handleFileSelect(event)">
                    <button type="button" class="btn-attach" onclick="document.getElementById('noteAttachments').click()">
                        <i class="fa fa-paperclip"></i>
                    </button>
                    <button type="button" class="btn-submit-note" onclick="saveNoteSidebar()">
                        {{ __('SUBMIT') }}
                    </button>
                </div>
            </div>

            <div class="notes-sidebar-body" id="notesContent">
                <!-- Notes timeline will be loaded here -->
            </div>
        </div>
    </div>

    <!-- Cancel Order Modal -->
    <div class="modal fade" id="cancelOrderModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content"
                style="background: var(--sales-bg-secondary); color: var(--sales-text-primary); border: 1px solid var(--sales-border-color);">
                <div class="modal-header" style="border-bottom: 1px solid var(--sales-border-color);">
                    <h5 class="modal-title">
                        <i class="fa fa-times-circle" style="color: #ef4444; margin-right: 8px;"></i>
                        {{ __('Cancel Order') }}
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                        style="color: var(--sales-text-primary);">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" style="padding: 25px;">
                    <div style="text-align: center; margin-bottom: 20px;">
                        <i class="fa fa-exclamation-triangle"
                            style="font-size: 48px; color: #ef4444; margin-bottom: 15px;"></i>
                        <h4 style="margin-bottom: 10px; color: var(--sales-text-primary);">{{ __('Are you sure?') }}</h4>
                        <p style="color: var(--sales-text-secondary); font-size: 14px; line-height: 1.5;">
                            {{ __('Do you want to cancel this order? This action will:') }}
                        </p>
                    </div>

                    <div
                        style="background: rgba(239, 68, 68, 0.1); padding: 15px; border-radius: 8px; margin-bottom: 20px; border-left: 4px solid #ef4444;">
                        <ul style="margin: 0; padding-left: 20px; color: var(--sales-text-primary);">
                            <li style="margin-bottom: 8px;">
                                <i class="fa fa-ban" style="color: #ef4444; margin-right: 5px;"></i>
                                {{ __('Change order status to "Cancelled"') }}
                            </li>
                            <li style="margin-bottom: 8px;">
                                <i class="fa fa-times" style="color: #ef4444; margin-right: 5px;"></i>
                                {{ __('Change confirm status to "Cancelled"') }}
                            </li>
                            <li style="margin-bottom: 8px;">
                                <i class="fa fa-link" style="color: #ef4444; margin-right: 5px;"></i>
                                {{ __('Disable payment link sent to customer') }}
                            </li>
                            <li>
                                <i class="fa fa-bell" style="color: #ef4444; margin-right: 5px;"></i>
                                {{ __('Send cancellation notification to customer') }}
                            </li>
                        </ul>
                    </div>

                    <div style="text-align: center;">
                        <p style="color: #ef4444; font-weight: 500; margin-bottom: 0;">
                            <i class="fa fa-info-circle"></i>
                            {{ __('This action cannot be undone!') }}
                        </p>
                    </div>
                </div>
                <div class="modal-footer" style="border-top: 1px solid var(--sales-border-color); padding: 15px 25px;">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fa fa-times"></i> {{ __('No, Keep Order') }}
                    </button>
                    <button type="button" class="btn btn-danger" id="confirmCancelBtn" onclick="executeCancelOrder()">
                        <i class="fa fa-times-circle"></i> {{ __('Yes, Cancel Order') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Success/Error Message Modal -->
    <div class="modal fade" id="messageModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content"
                style="background: var(--sales-bg-secondary); color: var(--sales-text-primary); border: 1px solid var(--sales-border-color);">
                <div class="modal-header" style="border-bottom: 1px solid var(--sales-border-color);">
                    <h5 class="modal-title" id="messageModalTitle">
                        <i class="fa fa-info-circle" id="messageModalIcon"></i>
                        <span id="messageModalTitleText">{{ __('Message') }}</span>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                        style="color: var(--sales-text-primary);">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" style="padding: 25px; text-align: center;">
                    <p id="messageModalBody" style="font-size: 16px; margin: 0;"></p>
                </div>
                <div class="modal-footer" style="border-top: 1px solid var(--sales-border-color); padding: 15px 25px;">
                    <button type="button" class="btn btn-primary" data-dismiss="modal" id="messageModalOkBtn">
                        <i class="fa fa-check"></i> {{ __('OK') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Confirm Order Modal -->
    <div class="modal fade" id="confirmOrderModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content"
                style="background: var(--sales-bg-secondary); color: var(--sales-text-primary); border: 1px solid var(--sales-border-color);">
                <div class="modal-header" style="border-bottom: 1px solid var(--sales-border-color);">
                    <h5 class="modal-title">
                        <i class="fa fa-check-circle" style="color: #10b981; margin-right: 8px;"></i>
                        {{ __('Confirm Order') }}
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                        style="color: var(--sales-text-primary);">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" style="padding: 25px;">
                    <div style="text-align: center; margin-bottom: 20px;">
                        <i class="fa fa-exclamation-triangle"
                            style="font-size: 48px; color: #f59e0b; margin-bottom: 15px;"></i>
                        <h4 style="margin-bottom: 10px; color: var(--sales-text-primary);">{{ __('Are you sure?') }}</h4>
                        <p style="color: var(--sales-text-secondary); font-size: 14px; line-height: 1.5;">
                            {{ __('Do you want to confirm this order? This action will:') }}
                        </p>
                    </div>

                    <div
                        style="background: var(--sales-hover-bg); padding: 15px; border-radius: 8px; margin-bottom: 20px; border-left: 4px solid #10b981;">
                        <ul style="margin: 0; padding-left: 20px; color: var(--sales-text-primary);">
                            <li style="margin-bottom: 8px;">
                                <i class="fa fa-check" style="color: #10b981; margin-right: 5px;"></i>
                                {{ __('Change order status to "Confirmed"') }}
                            </li>
                            <li style="margin-bottom: 8px;">
                                <i class="fa fa-user-tie" style="color: #3b82f6; margin-right: 5px;"></i>
                                {{ __('Assign you as the salesman for this order') }}
                            </li>
                            <li>
                                <i class="fa fa-bell" style="color: #f59e0b; margin-right: 5px;"></i>
                                {{ __('Send confirmation notification to customer') }}
                            </li>
                        </ul>
                    </div>

                    <div style="text-align: center;">
                        <p style="font-size: 13px; color: var(--sales-text-secondary); margin: 0;">
                            {{ __('This action cannot be undone.') }}
                        </p>
                    </div>
                </div>
                <div class="modal-footer" style="border-top: 1px solid var(--sales-border-color); padding: 15px 25px;">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"
                        style="background: var(--sales-hover-bg); color: var(--sales-text-primary); border: 1px solid var(--sales-border-color);">
                        <i class="fa fa-times"></i> {{ __('Cancel') }}
                    </button>
                    <button type="button" class="btn btn-success" id="confirmOrderBtn"
                        style="background: #10b981; color: #ffffff; border: none; margin-left: 10px;">
                        <i class="fa fa-check-circle"></i> {{ __('Yes, Confirm Order') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Global Variables (commented to fix duplicate declaration error)
        // let currentBookingId = null; // Already declared in first script
        // let mentionUsers = []; // Already declared in first script

        // Toggle expandable details
        function toggleDetails(bookingId) {
            const detailRow = document.getElementById('detail-' + bookingId);
            const icon = document.querySelector(`[data-booking-id="${bookingId}"] .expand-icon`);

            if (detailRow.classList.contains('show')) {
                detailRow.classList.remove('show');
                icon.classList.remove('expanded');
            } else {
                // Close all other details
                document.querySelectorAll('.detail-row.show').forEach(row => {
                    row.classList.remove('show');
                });
                document.querySelectorAll('.expand-icon.expanded').forEach(i => {
                    i.classList.remove('expanded');
                });

                // Open this detail
                detailRow.classList.add('show');
                icon.classList.add('expanded');
            }
        }

        // Modal functions
        function setPaidModal(bookingId) {
            $('#modal-paid-' + bookingId).modal('show');
        }

        // Confirm Order Modal
        function confirmOrderModal(bookingId) {
            // Reset modal to first step
            resetConfirmModal();

            // Get booking data
            const bookingRow = document.querySelector(`[data-booking-id="${bookingId}"]`);
            const orderNumber = bookingRow.querySelector('.order-link').textContent.trim();

            // Get customer info from the row
            const userDetails = bookingRow.querySelector('.user-details');
            const customerName = userDetails.querySelector('a, span').textContent.trim();
            const phoneElement = userDetails.querySelector('.user-phone');
            const customerPhone = phoneElement ? phoneElement.textContent.trim() : '';

            // Store booking data
            window.currentConfirmBooking = {
                id: bookingId,
                orderNumber: orderNumber,
                customerName: customerName,
                customerPhone: customerPhone,
                customerEmail: '', // Will be fetched from server if needed
                hasTicket: false,
                ticketFile: null,
                sendMethod: null
            };

            // Update modal title
            document.getElementById('confirmOrderModalLabel').innerHTML =
                `<i class="fa fa-check-circle"></i> ${orderNumber} - {{ __('Confirm Order') }}`;

            $('#confirmOrderModal').modal('show');
        }

        function resetConfirmModal() {
            // Hide all steps
            document.querySelectorAll('.confirm-step').forEach(step => {
                step.classList.remove('active');
            });

            // Show first step
            document.getElementById('confirmStep1').classList.add('active');

            // Reset buttons
            document.getElementById('prevBtn').style.display = 'none';
            document.getElementById('nextBtn').style.display = 'inline-block';
            document.getElementById('nextBtn').innerHTML = '{{ __("Next") }} <i class="fa fa-arrow-right"></i>';
            document.getElementById('confirmOrderBtn').style.display = 'none';

            // Clear file input
            document.getElementById('ticketFileInput').value = '';
            document.getElementById('ticketPreview').style.display = 'none';

            // Reset selections
            document.querySelectorAll('.send-option').forEach(option => {
                option.classList.remove('selected');
            });

            // Reset current step
            window.currentConfirmStep = 1;
        }

        let currentConfirmStep = 1;

        function nextStep() {
            const currentStep = document.getElementById(`confirmStep${currentConfirmStep}`);
            let nextStepNumber = currentConfirmStep + 1;

            // Step 1 -> Step 2 (always)
            if (currentConfirmStep === 1) {
                nextStepNumber = 2;
            }
            // Step 2 -> Step 3 or 5 (based on ticket selection)
            else if (currentConfirmStep === 2) {
                // This will be handled by selectTicketOption function
                return;
            }
            // Step 3 -> Step 4 (if ticket uploaded)
            else if (currentConfirmStep === 3) {
                if (!window.currentConfirmBooking.ticketFile) {
                    alert('{{ __("Please upload a ticket file") }}');
                    return;
                }
                nextStepNumber = 4;
            }
            // Step 4 -> Step 5 (if send method selected)
            else if (currentConfirmStep === 4) {
                if (!window.currentConfirmBooking.sendMethod) {
                    alert('{{ __("Please select a send method") }}');
                    return;
                }
                nextStepNumber = 5;
            }

            showStep(nextStepNumber);
        }

        function previousStep() {
            let prevStepNumber = currentConfirmStep - 1;

            // Step 5 -> Step 4 or 2 (based on ticket)
            if (currentConfirmStep === 5) {
                prevStepNumber = window.currentConfirmBooking.hasTicket ? 4 : 2;
            }
            // Step 4 -> Step 3
            else if (currentConfirmStep === 4) {
                prevStepNumber = 3;
            }
            // Step 3 -> Step 2
            else if (currentConfirmStep === 3) {
                prevStepNumber = 2;
            }
            // Step 2 -> Step 1
            else if (currentConfirmStep === 2) {
                prevStepNumber = 1;
            }

            showStep(prevStepNumber);
        }

        function showStep(stepNumber) {
            // Hide current step
            document.getElementById(`confirmStep${currentConfirmStep}`).classList.remove('active');

            // Show new step
            document.getElementById(`confirmStep${stepNumber}`).classList.add('active');

            // Update current step
            currentConfirmStep = stepNumber;

            // Update buttons
            updateStepButtons();

            // Special actions for specific steps
            if (stepNumber === 4) {
                populateCustomerInfo();
            } else if (stepNumber === 5) {
                populateFinalSummary();
            }
        }

        function updateStepButtons() {
            const prevBtn = document.getElementById('prevBtn');
            const nextBtn = document.getElementById('nextBtn');
            const confirmBtn = document.getElementById('confirmOrderBtn');

            // Show/hide previous button
            prevBtn.style.display = currentConfirmStep > 1 ? 'inline-block' : 'none';

            // Show/hide next vs confirm button
            if (currentConfirmStep === 5) {
                nextBtn.style.display = 'none';
                confirmBtn.style.display = 'inline-block';
            } else {
                nextBtn.style.display = 'inline-block';
                confirmBtn.style.display = 'none';
            }
        }

        function selectTicketOption(hasTicket) {
            window.currentConfirmBooking.hasTicket = hasTicket;

            if (hasTicket) {
                showStep(3); // Go to upload step
            } else {
                showStep(4); // Skip to send method
            }
        }

        function selectSendMethod(method) {
            // Remove previous selection
            document.querySelectorAll('.send-option').forEach(option => {
                option.classList.remove('selected');
            });

            // Add selection to clicked option
            event.currentTarget.classList.add('selected');

            // Store selection
            window.currentConfirmBooking.sendMethod = method;

            // Auto advance to final step
            setTimeout(() => {
                showStep(5);
            }, 500);
        }

        function populateCustomerInfo() {
            const booking = window.currentConfirmBooking;

            // Show current info while loading from server
            document.getElementById('customerPhone').textContent = booking.customerPhone || '{{ __("Loading...") }}';
            document.getElementById('customerEmail').textContent = '{{ __("Loading...") }}';

            // Fetch complete customer info from server
            $.ajax({
                url: '{{ route("report.admin.booking.customer-info") }}',
                method: 'GET',
                data: {
                    booking_id: booking.id
                },
                success: function (response) {
                    if (response.success && response.customer) {
                        // Update stored data
                        booking.customerEmail = response.customer.email;
                        booking.customerPhone = response.customer.phone;

                        // Update display
                        document.getElementById('customerPhone').textContent =
                            response.customer.phone || '{{ __("Not available") }}';
                        document.getElementById('customerEmail').textContent =
                            response.customer.email || '{{ __("Not available") }}';
                    }
                },
                error: function () {
                    document.getElementById('customerPhone').textContent =
                        booking.customerPhone || '{{ __("Not available") }}';
                    document.getElementById('customerEmail').textContent = '{{ __("Error loading") }}';
                }
            });
        }

        function populateFinalSummary() {
            const booking = window.currentConfirmBooking;
            const summaryHtml = `
                                                                                        <div class="summary-item">
                                                                                            <span class="summary-label">{{ __("Order") }}:</span>
                                                                                            <span class="summary-value">${booking.orderNumber}</span>
                                                                                        </div>
                                                                                        <div class="summary-item">
                                                                                            <span class="summary-label">{{ __("Customer") }}:</span>
                                                                                            <span class="summary-value">${booking.customerName}</span>
                                                                                        </div>
                                                                                        <div class="summary-item">
                                                                                            <span class="summary-label">{{ __("Has Ticket") }}:</span>
                                                                                            <span class="summary-value">${booking.hasTicket ? '{{ __("Yes") }}' : '{{ __("No") }}'}</span>
                                                                                        </div>
                                                                                        ${booking.hasTicket ? `
                                                                                        <div class="summary-item">
                                                                                            <span class="summary-label">{{ __("Ticket File") }}:</span>
                                                                                            <span class="summary-value">${booking.ticketFile ? booking.ticketFile.name : '{{ __("No file") }}'}</span>
                                                                                        </div>
                                                                                        ` : ''}
                                                                                        <div class="summary-item">
                                                                                            <span class="summary-label">{{ __("Send Method") }}:</span>
                                                                                            <span class="summary-value">${booking.sendMethod === 'whatsapp' ? '{{ __("WhatsApp") }}' : '{{ __("Email") }}'}</span>
                                                                                        </div>
                                                                                    `;

            document.getElementById('finalSummary').innerHTML = summaryHtml;
        }

        // File upload handlers
        document.getElementById('ticketFileInput').addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (file) {
                handleTicketFile(file);
            }
        });

        function handleTicketFile(file) {
            // Validate file
            const maxSize = 10 * 1024 * 1024; // 10MB
            const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'application/pdf'];

            if (file.size > maxSize) {
                alert('{{ __("File size must be less than 10MB") }}');
                return;
            }

            if (!allowedTypes.includes(file.type)) {
                alert('{{ __("Only JPG, PNG and PDF files are allowed") }}');
                return;
            }

            // Store file
            window.currentConfirmBooking.ticketFile = file;

            // Show preview
            showTicketPreview(file);
        }

        function showTicketPreview(file) {
            const preview = document.getElementById('ticketPreview');
            const thumbnail = document.getElementById('ticketThumbnail');
            const fileName = document.getElementById('ticketFileName');
            const fileSize = document.getElementById('ticketFileSize');

            // Set file info
            fileName.textContent = file.name;
            fileSize.textContent = formatFileSize(file.size);

            // Set thumbnail
            if (file.type.startsWith('image/')) {
                const img = document.createElement('img');
                img.src = URL.createObjectURL(file);
                thumbnail.innerHTML = '';
                thumbnail.appendChild(img);
            } else if (file.type === 'application/pdf') {
                thumbnail.innerHTML = '<i class="fa fa-file-pdf-o" style="color: #ef4444; font-size: 24px;"></i>';
                thumbnail.style.background = 'rgba(239, 68, 68, 0.1)';
            }

            preview.style.display = 'block';
        }

        function removeTicketFile() {
            window.currentConfirmBooking.ticketFile = null;
            document.getElementById('ticketFileInput').value = '';
            document.getElementById('ticketPreview').style.display = 'none';
        }

        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }

        // Drag and drop for ticket upload
        const uploadArea = document.getElementById('ticketUploadArea');
        if (uploadArea) {
            uploadArea.addEventListener('dragover', function (e) {
                e.preventDefault();
                this.classList.add('dragover');
            });

            uploadArea.addEventListener('dragleave', function (e) {
                e.preventDefault();
                this.classList.remove('dragover');
            });

            uploadArea.addEventListener('drop', function (e) {
                e.preventDefault();
                this.classList.remove('dragover');
                const files = e.dataTransfer.files;
                if (files.length > 0) {
                    handleTicketFile(files[0]);
                }
            });
        }

        function finalConfirmOrder() {
            const booking = window.currentConfirmBooking;

            // Disable button and show loading
            $('#confirmOrderBtn').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> {{ __("Processing...") }}');

            // Prepare form data
            const formData = new FormData();
            formData.append('booking_id', booking.id);
            formData.append('has_ticket', booking.hasTicket ? '1' : '0');
            formData.append('send_method', booking.sendMethod);

            if (booking.hasTicket && booking.ticketFile) {
                formData.append('ticket_file', booking.ticketFile);
            }

            formData.append('_token', '{{ csrf_token() }}');

            showLoading();

            $.ajax({
                url: '{{ route("report.admin.booking.confirm-order") }}',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    hideLoading();
                    $('#confirmOrderModal').modal('hide');

                    if (response.success) {
                        if (response.whatsapp_url) {
                            // Open WhatsApp
                            window.open(response.whatsapp_url, '_blank');
                        }

                        alert(response.message || '{{ __("Order confirmed successfully") }}');
                        window.location.reload();
                    } else {
                        alert(response.message || '{{ __("Something went wrong") }}');
                    }
                },
                error: function (xhr) {
                    hideLoading();
                    $('#confirmOrderModal').modal('hide');
                    alert('Error: ' + (xhr.responseJSON?.message || '{{ __("Something went wrong") }}'));
                },
                complete: function () {
                    // Re-enable button
                    $('#confirmOrderBtn').prop('disabled', false).html('<i class="fa fa-check-circle"></i> {{ __("Confirm & Send") }}');
                }
            });
        }

        // Set Paid functionality
        $(document).on('click', '#set_paid_btn', function (e) {
            var id = $(this).data('id');
            $.ajax({
                url: '{{ url('/') }}/booking/setPaidAmount',
                data: {
                    id: id,
                    remain: $('#modal-paid-' + id + ' #set_paid_input').val(),
                },
                dataType: 'json',
                type: 'post',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function (res) {
                    alert(res.message);
                    window.location.reload();
                },
                error: function (xhr) {
                    alert('Error: ' + (xhr.responseJSON?.message || 'Something went wrong'));
                }
            });
        });

        // Show/Hide Loading
        function showLoading() {
            document.getElementById('loading-overlay').classList.add('show');
        }

        function hideLoading() {
            document.getElementById('loading-overlay').classList.remove('show');
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', function () {
            // Show loading on form submit
            document.getElementById('sales-filter-form').addEventListener('submit', function () {
                showLoading();
            });

            // Auto-hide loading after page load
            setTimeout(hideLoading, 500);
        });

        // Table sorting
        document.querySelectorAll('.sortable').forEach(th => {
            th.addEventListener('click', function () {
                const sortBy = this.dataset.sort;
                const currentUrl = new URL(window.location.href);
                const currentSort = currentUrl.searchParams.get('sort_by');
                const currentOrder = currentUrl.searchParams.get('sort_order') || 'desc';

                // Toggle order if same column
                const newOrder = (currentSort === sortBy && currentOrder === 'desc') ? 'asc' : 'desc';

                currentUrl.searchParams.set('sort_by', sortBy);
                currentUrl.searchParams.set('sort_order', newOrder);

                window.location.href = currentUrl.toString();
            });
        });

        // Notes Sidebar Functions
        function openNotesModal(bookingId) {
            currentBookingId = bookingId;

            // Get booking order number for display
            const orderNumber = $('[data-booking-id="' + bookingId + '"]').closest('tr').find('.order-link').text().trim();
            $('#orderNoteNumber').text(orderNumber);

            // Clear textarea
            $('#sidebarNoteTextarea').val('');

            // Open sidebar
            $('#notesSidebar').addClass('open');
            $('body').css('overflow', 'hidden');

            loadNotes(bookingId);

            // Focus on textarea
            setTimeout(() => {
                $('#sidebarNoteTextarea').focus();
            }, 400);
        }

        function closeNotesSidebar() {
            $('#notesSidebar').removeClass('open');
            $('body').css('overflow', '');
        }

        // Keyboard shortcut: Ctrl+Enter to submit note
        $(document).on('keydown', '#sidebarNoteTextarea', function (e) {
            if ((e.ctrlKey || e.metaKey) && e.keyCode === 13) {
                e.preventDefault();
                saveNoteSidebar();
            }
        });

        // Rest of JavaScript functions will be added in the next part...

        // Mention Functions
        function toggleMentionDropdown() {
            const dropdown = document.getElementById('mentionDropdown');
            if (dropdown.style.display === 'none' || dropdown.style.display === '') {
                // Load all users when opening dropdown
                loadMentionUsers('');
                dropdown.style.display = 'block';
                setTimeout(() => {
                    document.getElementById('mentionSearch').focus();
                }, 100);
            } else {
                dropdown.style.display = 'none';
                // Clear search input when closing
                document.getElementById('mentionSearch').value = '';
            }
        }

        function loadMentionUsers(searchTerm = '') {
            showLoading();

            $.ajax({
                url: '{{ route("user.admin.getForSelect2") }}',
                method: 'GET',
                data: {
                    q: searchTerm // Search by name or phone
                },
                success: function (response) {
                    hideLoading();
                    if (response && response.results) {
                        mentionUsers = response.results;
                        displayMentionUsers(mentionUsers);
                    } else {
                        $('#mentionUsersList').html('<div class="mention-empty"><i class="fa fa-exclamation-triangle"></i>{{ __("No users found") }}</div>');
                    }
                },
                error: function () {
                    hideLoading();
                    $('#mentionUsersList').html('<div class="mention-empty"><i class="fa fa-exclamation-triangle"></i>{{ __("Failed to load users") }}</div>');
                }
            });
        }

        function displayMentionUsers(users) {
            if (!users || users.length === 0) {
                $('#mentionUsersList').html('<div class="mention-empty"><i class="fa fa-users"></i>{{ __("No users found") }}</div>');
                $('#mentionUsersCount').text('0 {{ __("users") }}');
                return;
            }

            // Update counter
            $('#mentionUsersCount').text(users.length + ' {{ __("users") }}');

            let html = '';
            users.forEach(user => {
                const userName = user.text || user.first_name || 'Unknown';
                const userRole = user.role_name || 'User';
                const userId = user.id;
                const userAvatar = user.avatar_url || null;
                const userPhone = user.phone || '';
                const userEmail = user.email || '';
                const firstLetter = userName.charAt(0).toUpperCase();
                const bgColor = getRandomColor();

                // Escape single quotes in username for onclick
                const safeUserName = userName.replace(/'/g, "\\'");

                const avatarHtml = userAvatar
                    ? `<img src="${userAvatar}" alt="${userName}">`
                    : `<div class="mention-user-avatar-letter" style="background: ${bgColor};">${firstLetter}</div>`;

                const phoneHtml = userPhone ? `<div class="mention-user-phone"><i class="fa fa-phone"></i> ${userPhone}</div>` : '';

                html += `
                                                                                            <div class="mention-user-item" onclick="insertMention('${safeUserName}', ${userId})" data-user-id="${userId}">
                                                                                                <div class="mention-user-avatar">
                                                                                                    ${avatarHtml}
                                                                                                </div>
                                                                                                <div class="mention-user-info">
                                                                                                    <div class="mention-user-name">${userName}</div>
                                                                                                    ${phoneHtml}
                                                                                                    <div class="mention-user-role"><i class="fa fa-user-tag"></i> ${userRole}</div>
                                                                                                </div>
                                                                                            </div>
                                                                                        `;
            });

            $('#mentionUsersList').html(html);
        }

        function filterMentionUsers() {
            const searchTerm = $('#mentionSearch').val().trim();
            // Load users from server with search term
            loadMentionUsers(searchTerm);
        }

        function insertMention(userName, userId) {
            const textarea = document.getElementById('sidebarNoteTextarea');
            const mention = `@${userName} `;

            // Get current cursor position
            const cursorPos = textarea.selectionStart;
            const textBefore = textarea.value.substring(0, cursorPos);
            const textAfter = textarea.value.substring(cursorPos);

            // Insert mention at cursor position
            textarea.value = textBefore + mention + textAfter;

            // Set cursor position after mention
            const newPos = cursorPos + mention.length;
            textarea.setSelectionRange(newPos, newPos);
            textarea.focus();

            // Close dropdown
            document.getElementById('mentionDropdown').style.display = 'none';
        }

        // Close mention dropdown when clicking outside
        $(document).on('click', function (e) {
            const dropdown = document.getElementById('mentionDropdown');
            const button = $('.btn-mention')[0];

            if (dropdown && dropdown.style.display !== 'none') {
                if (!dropdown.contains(e.target) && !button.contains(e.target)) {
                    dropdown.style.display = 'none';
                }
            }
        });

        function loadNotes(bookingId) {
            console.log('Loading notes for booking:', bookingId);
            showLoading();

            $.ajax({
                url: '{{ route("report.admin.booking.get-notes") }}',
                method: 'GET',
                data: {
                    booking_id: bookingId,
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {
                    console.log('Notes response:', response);
                    hideLoading();
                    if (response.success) {
                        displayNotes(response.notes);
                    } else {
                        console.error('Failed to load notes:', response.message);
                        $('#notesTimeline').html('<div class="empty-notes"><i class="fa fa-exclamation-triangle"></i><p>{{ __("Failed to load notes") }}</p></div>');
                    }
                },
                error: function (xhr, status, error) {
                    console.error('AJAX error loading notes:', xhr.responseText);
                    hideLoading();
                    $('#notesTimeline').html('<div class="empty-notes"><i class="fa fa-exclamation-triangle"></i><p>{{ __("Error loading notes") }}</p></div>');
                }
            });
        }

        function displayNotes(notes) {
            console.log('Displaying notes:', notes);

            if (!notes || notes.length === 0) {
                console.log('No notes to display');
                $('#notesTimeline').html('<div class="empty-notes"><i class="fa fa-sticky-note-o"></i><p>{{ __("No notes yet") }}</p></div>');
                return;
            }

            console.log('Found', notes.length, 'notes');
            let html = '';
            notes.forEach(note => {
                const userName = note.user_name || '{{ __("Unknown User") }}';
                const userAvatar = note.user_avatar;
                const firstLetter = userName.charAt(0).toUpperCase();
                const colors = ['#6366f1', '#8b5cf6', '#ec4899', '#f59e0b', '#10b981', '#3b82f6', '#14b8a6', '#f97316'];
                const colorIndex = firstLetter.charCodeAt(0) % colors.length;
                const bgColor = colors[colorIndex];

                const avatarHtml = userAvatar
                    ? `<img src="${userAvatar}" alt="${userName}">`
                    : `<div class="note-avatar-letter" style="background: ${bgColor};">${firstLetter}</div>`;

                let attachmentsHtml = '';
                if (note.attachments && note.attachments.length > 0) {
                    attachmentsHtml = '<div class="note-attachments">';
                    note.attachments.forEach(attachment => {
                        const extension = attachment.extension.toLowerCase();
                        const isImage = ['jpg', 'jpeg', 'png', 'gif'].includes(extension);
                        const isPdf = extension === 'pdf';
                        const isDoc = ['doc', 'docx'].includes(extension);

                        let iconHtml = '';
                        if (isImage) {
                            iconHtml = `<img src="${attachment.url}" class="attachment-thumbnail" alt="${attachment.original_name}">`;
                        } else if (isPdf) {
                            iconHtml = `<div class="attachment-icon-wrapper attachment-icon-pdf-wrapper"><i class="fa fa-file-pdf-o"></i></div>`;
                        } else if (isDoc) {
                            iconHtml = `<div class="attachment-icon-wrapper attachment-icon-doc-wrapper"><i class="fa fa-file-word-o"></i></div>`;
                        } else {
                            iconHtml = `<div class="attachment-icon-wrapper attachment-icon-text-wrapper"><i class="fa fa-file-text-o"></i></div>`;
                        }

                        const sizeFormatted = formatFileSize(attachment.size);

                        attachmentsHtml += `
                                                                                                    <a href="${attachment.url}" target="_blank" class="note-attachment">
                                                                                                        ${iconHtml}
                                                                                                        <div class="attachment-info">
                                                                                                            <div class="attachment-name">${attachment.original_name}</div>
                                                                                                            <div class="attachment-size">${sizeFormatted}</div>
                                                                                                        </div>
                                                                                                    </a>
                                                                                                `;
                    });
                    attachmentsHtml += '</div>';
                }

                html += `
                                                                                            <div class="note-item">
                                                                                                <div class="note-avatar">
                                                                                                    ${avatarHtml}
                                                                                                </div>
                                                                                                <div class="note-content">
                                                                                                    <div class="note-header">
                                                                                                        <span class="note-author">${userName}</span>
                                                                                                        <span class="note-time">${note.created_at}</span>
                                                                                                    </div>
                                                                                                    <div class="note-content">${note.content}</div>
                                                                                                    ${attachmentsHtml}
                                                                                                </div>
                                                                                            </div>
                                                                                        `;
            });

            $('#notesTimeline').html(html);
        }

        // File attachment handlers for notes
        $('#noteAttachments').on('change', function (e) {
            const files = Array.from(e.target.files);
            if (files.length > 0) {
                displaySelectedFiles(files);
            }
        });

        function displaySelectedFiles(files) {
            const preview = $('#attachmentPreview');
            preview.empty();

            files.forEach((file, index) => {
                if (file.size > 10 * 1024 * 1024) { // 10MB limit
                    alert(`{{ __('File :filename is too large. Maximum size is 10MB.', ['filename' => '']) }}${file.name}`);
                    return;
                }

                const isImage = file.type.startsWith('image/');
                const isPdf = file.type === 'application/pdf';
                const isDoc = file.type.includes('word') || file.name.toLowerCase().endsWith('.doc') || file.name.toLowerCase().endsWith('.docx');

                let thumbnailHtml = '';
                if (isImage) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        $(`#file-thumb-${index}`).html(`<img src="${e.target.result}" class="attachment-thumbnail" alt="${file.name}">`);
                    };
                    reader.readAsDataURL(file);
                    thumbnailHtml = `<div id="file-thumb-${index}" class="attachment-thumbnail"></div>`;
                } else if (isPdf) {
                    thumbnailHtml = `<div class="attachment-icon-wrapper attachment-icon-pdf-wrapper"><i class="fa fa-file-pdf-o"></i></div>`;
                } else if (isDoc) {
                    thumbnailHtml = `<div class="attachment-icon-wrapper attachment-icon-doc-wrapper"><i class="fa fa-file-word-o"></i></div>`;
                } else {
                    thumbnailHtml = `<div class="attachment-icon-wrapper attachment-icon-text-wrapper"><i class="fa fa-file-text-o"></i></div>`;
                }

                const sizeFormatted = formatFileSize(file.size);

                const fileHtml = `
                                                                                            <div class="attachment-item" data-file-index="${index}">
                                                                                                ${thumbnailHtml}
                                                                                                <div class="attachment-info">
                                                                                                    <div class="attachment-name">${file.name}</div>
                                                                                                    <div class="attachment-size">${sizeFormatted}</div>
                                                                                                </div>
                                                                                                <span class="attachment-item-remove" onclick="removeSelectedFile(${index})">
                                                                                                    <i class="fa fa-times"></i>
                                                                                                </span>
                                                                                            </div>
                                                                                        `;

                preview.append(fileHtml);
            });

            // Store files for later use
            window.selectedNoteFiles = files;
        }

        function removeSelectedFile(index) {
            $(`[data-file-index="${index}"]`).remove();

            // Remove from files array
            const files = Array.from(window.selectedNoteFiles || []);
            files.splice(index, 1);
            window.selectedNoteFiles = files;

            // Update file input
            const dt = new DataTransfer();
            files.forEach(file => dt.items.add(file));
            document.getElementById('noteAttachments').files = dt.files;

            // Re-display files with new indices
            if (files.length > 0) {
                displaySelectedFiles(files);
            }
        }

        function saveNoteSidebar() {
            const noteText = $('#sidebarNoteTextarea').val().trim();
            const files = window.selectedNoteFiles || [];

            if (!noteText && files.length === 0) {
                alert('{{ __("Please enter a note or attach a file") }}');
                return;
            }

            if (!currentBookingId) {
                alert('{{ __("No booking selected") }}');
                return;
            }

            // Disable button
            $('.btn-submit-note').prop('disabled', true).text('{{ __("Saving...") }}');

            const formData = new FormData();
            formData.append('booking_id', currentBookingId);
            formData.append('note', noteText);

            // Add files
            files.forEach((file, index) => {
                formData.append(`attachments[${index}]`, file);
            });

            formData.append('_token', '{{ csrf_token() }}');

            showLoading();

            $.ajax({
                url: '{{ route("report.admin.booking.add-note") }}',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    hideLoading();
                    if (response.success) {
                        // Clear form
                        $('#sidebarNoteTextarea').val('');
                        $('#attachmentPreview').empty();
                        $('#noteAttachments').val('');
                        window.selectedNoteFiles = [];

                        // Reload notes
                        loadNotes(currentBookingId);

                        // Update note counter in main table
                        const noteIcon = $(`[data-booking-id="${currentBookingId}"] .note-icon-wrapper`);
                        const currentCounter = noteIcon.find('.note-counter');
                        if (currentCounter.length > 0) {
                            const currentCount = parseInt(currentCounter.text()) || 0;
                            currentCounter.text(currentCount + 1);
                        } else {
                            noteIcon.append('<span class="note-counter">1</span>');
                        }
                    } else {
                        alert(response.message || '{{ __("Failed to save note") }}');
                    }
                },
                error: function (xhr) {
                    hideLoading();
                    alert('{{ __("Error: ") }}' + (xhr.responseJSON?.message || '{{ __("Something went wrong") }}'));
                },
                complete: function () {
                    $('.btn-submit-note').prop('disabled', false).text('{{ __("Add Note") }}');
                }
            });
        }

        function getRandomColor() {
            const colors = ['#6366f1', '#8b5cf6', '#ec4899', '#f59e0b', '#10b981', '#3b82f6', '#14b8a6', '#f97316'];
            return colors[Math.floor(Math.random() * colors.length)];
        }

                                                                // Make Order Pending Function (commented to fix duplicate function error)
                                                                /* window.makePendingOrder = function(bookingId) { // Already defined in first script
                                                                    console.log('makePendingOrder called with ID:', bookingId); // Debug log
                                                                    if (!confirm('{{ __("Are you sure you want to make this order pending?") }}')) {
        return;
                                                                    }

        showLoading();

        $.ajax({
            url: '{{ route("report.admin.booking.make-pending") }}',
            method: 'POST',
            data: {
                booking_id: bookingId,
                _token: '{{ csrf_token() }}'
            },
            success: function (response) {
                hideLoading();
                if (response.success) {
                    alert(response.message || '{{ __("Order status changed to pending successfully") }}');
                    window.location.reload();
                } else {
                    alert(response.message || '{{ __("Something went wrong") }}');
                }
            },
            error: function (xhr) {
                hideLoading();
                alert('Error: ' + (xhr.responseJSON?.message || '{{ __("Something went wrong") }}'));
            }
        });
                                                                };

        // Debug: Check if function is defined (commented to fix duplicate)
        // console.log('makePendingOrder function defined:', typeof window.makePendingOrder);

        // Also define it as a regular function for backward compatibility (commented to fix duplicate)
        // function makePendingOrder(bookingId) {
        //     return window.makePendingOrder(bookingId);
        // } */

        // Override finalConfirmOrder to fix WhatsApp opening issue
        window.originalFinalConfirmOrder = window.finalConfirmOrder;
        window.finalConfirmOrder = function () {
            const booking = window.currentConfirmBooking;

            // Disable button and show loading
            $('#confirmOrderBtn').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> {{ __("Processing...") }}');

            // Prepare form data
            const formData = new FormData();
            formData.append('booking_id', booking.id);
            formData.append('has_ticket', booking.hasTicket ? '1' : '0');
            formData.append('send_method', booking.sendMethod);

            if (booking.hasTicket && booking.ticketFile) {
                formData.append('ticket_file', booking.ticketFile);
            }

            formData.append('_token', '{{ csrf_token() }}');

            showLoading();

            $.ajax({
                url: '{{ route("report.admin.booking.confirm-order") }}',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    hideLoading();

                    console.log('Server response:', response); // Debug log

                    if (response.success) {
                        $('#confirmOrderModal').modal('hide');

                        // Show success message first
                        alert(response.message || '{{ __("Order confirmed successfully") }}');

                        if (response.whatsapp_url) {
                            console.log('Opening WhatsApp URL:', response.whatsapp_url); // Debug log
                            // Try to open WhatsApp immediately after user clicks OK on alert
                            setTimeout(function () {
                                try {
                                    const opened = window.open(response.whatsapp_url, '_blank');
                                    if (!opened || opened.closed || typeof opened.closed == 'undefined') {
                                        console.log('Popup blocked, trying alternative method');
                                        // Fallback: Create a link and click it
                                        const link = document.createElement('a');
                                        link.href = response.whatsapp_url;
                                        link.target = '_blank';
                                        link.rel = 'noopener noreferrer';
                                        document.body.appendChild(link);
                                        link.click();
                                        document.body.removeChild(link);
                                    }
                                } catch (e) {
                                    console.error('Error opening WhatsApp:', e);
                                    // Manual fallback - show URL
                                    if (confirm('{{ __("Click OK to copy WhatsApp link and open it manually") }}')) {
                                        navigator.clipboard.writeText(response.whatsapp_url).then(() => {
                                            alert('{{ __("WhatsApp link copied to clipboard") }}');
                                        }).catch(() => {
                                            prompt('{{ __("Copy this WhatsApp link:") }}', response.whatsapp_url);
                                        });
                                    }
                                }
                            }, 500); // Small delay to ensure alert is dismissed
                        }

                        // Reload page after a delay
                        setTimeout(function () {
                            window.location.reload();
                        }, 2000);
                    } else {
                        $('#confirmOrderModal').modal('hide');
                        alert(response.message || '{{ __("Something went wrong") }}');
                    }
                },
                error: function (xhr) {
                    hideLoading();
                    $('#confirmOrderModal').modal('hide');
                    alert('Error: ' + (xhr.responseJSON?.message || '{{ __("Something went wrong") }}'));
                },
                complete: function () {
                    // Re-enable button
                    $('#confirmOrderBtn').prop('disabled', false).html('<i class="fa fa-check-circle"></i> {{ __("Confirm & Send") }}');
                }
            });
        };
    </script>

    <!-- WhatsApp URL Fix Script -->
    <script>
        // Override window.open for WhatsApp URLs
        const originalWindowOpen = window.open;
        window.open = function (url, target, features) {
            // Check if it's a WhatsApp URL
            if (url && url.includes('wa.me')) {
                // Force new tab/window for WhatsApp
                const newWindow = originalWindowOpen.call(this, url, '_blank', 'noopener,noreferrer');

                // Additional attempts if first one fails
                setTimeout(() => {
                    if (!newWindow || newWindow.closed || typeof newWindow.closed == 'undefined') {
                        // Try creating a temporary link and clicking it
                        const link = document.createElement('a');
                        link.href = url;
                        link.target = '_blank';
                        link.rel = 'noopener noreferrer';
                        document.body.appendChild(link);
                        link.click();
                        document.body.removeChild(link);
                    }
                }, 100);

                return newWindow;
            }

            // For non-WhatsApp URLs, use original function
            return originalWindowOpen.call(this, url, target, features);
        };
    </script>

    <!-- Clean Notes Sidebar JavaScript -->
    <script src="{{ asset('js/notes-sidebar.js') }}"></script>
@endsection