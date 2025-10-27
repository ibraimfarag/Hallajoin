@php $lang_local = app()->getLocale() @endphp
<div class="booking-review">
    <h4 class="booking-review-title">{{ __('Your Booking') }}</h4>
    <div class="booking-review-content">
        <div class="review-section">
            <div class="service-info">
                <div>
                    @php
                        $service_translation = $service->translate($lang_local);
                    @endphp
                    <h3 class="service-name"><a href="{{ $service->getDetailUrl() }}">{!! clean($service_translation->title) !!}</a></h3>
                    @if ($service_translation->address)
                        <p class="address"><i class="fa fa-map-marker orange"></i>
                            {{ $service_translation->address }}
                        </p>
                    @endif
                </div>
                @php $vendor = $service->author; @endphp
                @if ($vendor->hasPermission('dashboard_vendor_access') and !$vendor->hasPermission('dashboard_access'))
                    <div class="mt-1">
                        <i class="icofont-info-circle"></i>
                        {{ __('Vendor') }}: <a href="{{ route('user.profile', ['id' => $vendor->id]) }}"
                            target="_blank">{{ $vendor->getDisplayName() }}</a>
                    </div>
                @endif
            </div>
        </div>
        <div class="review-section">
            <ul class="review-list">
                {{-- @if ($booking->start_date)
                    <li>
                        <div class="label">{{ __('Start date:') }}</div>
                        <div class="val">
                            {{ display_date($booking->start_date) }}
                        </div>
                    </li>
            @php
            dd($service_translation);
            @endphp
                @endif --}}
                @php
                    // Decode the sections JSON
                    $sections = json_decode($service_translation->sections, true);
                @endphp

                @if ($sections)
                    @foreach ($sections as $section)
                        @if (isset($section['title']) && $section['title'] === 'Cancellation Policy')
                        <div class="label"><h6>{{ __('Cancellation Policy') }}</h6></div>
                            <li>
                            


                                <div class="val">
                                    <ul class="dash-list">
                                        @foreach ($section['items'] as $item)
                                            <li> {{ $item['title'] }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </li>
                        @endif
                    @endforeach
                @endif

            </ul>
        </div>
        @do_action('booking.checkout.before_total_review')
        <div class="review-section total-review">
            <ul class="review-list">
                @php $person_types = $booking->getJsonMeta('person_types') @endphp
                {{-- @if (!empty($person_types))
                    @foreach ($person_types as $type)
                        <li>
                            <div class="label">{{ $type['name_' . $lang_local] ?? __($type['name']) }}:
                                {{ $type['number'] }} * {!! format_money($type['price']) !!}</div>
                            <div class="val">
                                {!! format_money($type['price'] * $type['number']) !!}
                            </div>
                        </li>
                    @endforeach
                @else
                    <li>
                        <div class="label">{{ __('Guests') }}: {{ $booking->total_guests }} *
                            {!! format_money($booking->getMeta('base_price')) !!}</div>
                        <div class="val">
                            {!! format_money($booking->getMeta('base_price') * $booking->total_guests) !!}
                        </div>
                    </li>
                @endif --}}
                @php $extra_price = $booking->getJsonMeta('extra_price') @endphp
                @if (!empty($extra_price))
                    <li>
                        <div class="label-title"><strong>{{ __('Extra Prices:') }}</strong></div>
                    </li>
                    <li class="no-flex">
                        <ul>
                            @foreach ($extra_price as $type)
                                <li>
                                    <div class="label">{{ $type['name_' . $lang_local] ?? __($type['name']) }}:</div>
                                    <div class="val">
                                        {!! format_money($type['total'] ?? 0) !!}
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                @endif
                @php $discount_by_people = $booking->getJsonMeta('discount_by_people')@endphp
                @if (!empty($discount_by_people))
                    <li>
                        <div class="label-title"><strong>{{ __('Discounts:') }}</strong></div>
                    </li>
                    <li class="no-flex">
                        <ul>
                            @foreach ($discount_by_people as $type)
                                <li>
                                    <div class="label">
                                        @if (!$type['to'])
                                            {{ __('from :from guests', ['from' => $type['from']]) }}
                                        @else
                                            {{ __(':from - :to guests', ['from' => $type['from'], 'to' => $type['to']]) }}
                                        @endif
                                        :
                                    </div>
                                    <div class="val">
                                        - {!! format_money($type['total'] ?? 0) !!}
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                @endif
                @php
                    $list_all_fee = [];
                    if (!empty($booking->buyer_fees)) {
                        $buyer_fees = json_decode($booking->buyer_fees, true);
                        $list_all_fee = $buyer_fees;
                    }
                    if (!empty(($vendor_service_fee = $booking->vendor_service_fee))) {
                        $list_all_fee = array_merge($list_all_fee, $vendor_service_fee);
                    }
                @endphp
                @if (!empty($list_all_fee))
                    @foreach ($list_all_fee as $item)
                        @php
                            $fee_price = $item['price'];
                            if (!empty($item['unit']) and $item['unit'] == 'percent') {
                                $fee_price = ($booking->total_before_fees / 100) * $item['price'];
                            }
                        @endphp
                        <li>
                            <div class="label">
                                {{ $item['name_' . $lang_local] ?? $item['name'] }}
                                <i class="icofont-info-circle" data-toggle="tooltip" data-placement="top"
                                    title="{{ $item['desc_' . $lang_local] ?? $item['desc'] }}"></i>
                                @if (!empty($item['per_person']) and $item['per_person'] == 'on')
                                    : {{ $booking->total_guests }} * {!! format_money($fee_price) !!}
                                @endif
                            </div>
                            <div class="val">
                                @if (!empty($item['per_person']) and $item['per_person'] == 'on')
                                    {!! format_money($fee_price * $booking->total_guests) !!}
                                @else
                                    {!! format_money($fee_price) !!}
                                @endif
                            </div>
                        </li>
                    @endforeach
                @endif



                <li><div class="label"><h6>{{ __('booking summary') }}</h6></div></li>

                {{-- Single Ticket Container for All Bookings --}}
                @php
                    $allBookings = isset($bookings) && $bookings->count() > 1 ? $bookings : collect([$booking]);
                @endphp

                <li>
                    <div class="container">
                        <div class="item">
                            <div class="item-left">
                                {{-- Loop through all activities in the cart --}}
                                @foreach($allBookings as $loopIndex => $cartBooking)
                                    @php
                                        $cartService = $cartBooking->service;
                                        $cart_service_translation = $cartService ? $cartService->translate($lang_local) : null;
                                    @endphp

                                    @if($cart_service_translation)
                                        {{-- Activity Section --}}
                                        <div class="activity-section" style="margin-bottom: {{ $loop->last ? '0' : '12px' }}; {{ $loop->last ? '' : 'padding-bottom: 12px; border-bottom: 1px dashed #ddd;' }}">
                                            {{-- Activity Title --}}
                                            <h2 class="title theme-color" style="margin-bottom: 6px; font-size: 16px; line-height: 1.3;">{!! clean($cart_service_translation->title) !!}</h2>

                                            {{-- Date --}}
                                            @if ($cartBooking->start_date)
                                                <div class="sce" style="margin-bottom: 5px; font-size: 13px;">
                                                    <strong>
                                                        <span class="theme-color">{{ \Carbon\Carbon::parse($cartBooking->start_date)->format('D, d M Y') }}</span>
                                                    </strong>
                                                </div>
                                            @endif

                                            {{-- Guest Details --}}
                                            <div class="loc" style="margin-bottom: 5px; font-size: 13px; line-height: 1.4;">
                                                @php
                                                    $person_types = $cartBooking->getJsonMeta('person_types');
                                                @endphp
                                                @if (!empty($person_types))
                                                    @php
                                                        $text = '';
                                                        foreach ($person_types as $type) {
                                                            $person_type =
                                                                ($type['name_' . $lang_local] ?? __($type['name'])) .
                                                                ': ' .
                                                                '<strong>' .
                                                                $type['number'] .
                                                                '</strong>' .
                                                                ' x ' .
                                                                '<strong>' .
                                                                format_money($type['price']) .
                                                                '</strong>';
                                                            $text .= $person_type . ' ';
                                                        }
                                                    @endphp
                                                    <div style="margin: 0;">{!! $text !!}</div>
                                                @else
                                                    <div style="margin: 0;">{{ $cartBooking->total_guests }} {{ __('Guests') }}</div>
                                                @endif
                                            </div>

                                            {{-- Extra Prices --}}
                                            @php $extra_price = $cartBooking->getJsonMeta('extra_price') @endphp
                                            @if (!empty($extra_price))
                                                <div class="extra-prices-section" style="margin-bottom: 5px; font-size: 12px;">
                                                    <strong>{{ __('Extra Prices:') }}</strong>
                                                    <ul style="margin: 2px 0 0 0; padding-left: 18px; list-style: disc;">
                                                        @foreach ($extra_price as $type)
                                                            <li>
                                                                {{ $type['name_' . $lang_local] ?? __($type['name']) }}:
                                                                <strong>{!! format_money($type['total'] ?? 0) !!}</strong>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            @endif

                                            {{-- Activity Total --}}
                                            <div class="activity-total" style="margin-top: 6px; font-size: 13px;">
                                                <span>{{ __('Activity Total') }}: </span>
                                                <strong style="color: #ff8c00;">{!! format_money($cartBooking->total) !!}</strong>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div> <!-- end item-left -->

                            <div class="item-right">
                                {{-- Grand Total in the right side --}}
                                @php
                                    $cartTotal = $allBookings->sum('total');
                                @endphp
                                
                                <div class="loc-price" style="text-align: center;">
                                    <span style="font-size: 12px; display: block; margin-bottom: 3px;">{{ __('Grand Total') }}</span>
                                    <strong>
                                        <h4 style="margin: 0; color: #ff8c00; font-size: 22px;">{!! format_money($cartTotal) !!}</h4>
                                    </strong>
                                </div>

                                {{-- Number of activities --}}
                                @if($allBookings->count() > 1)
                                    <div class="activities-count" style="margin-top: 12px; padding-top: 12px; border-top: 1px solid #eee; text-align: center; font-size: 12px; color: #666;">
                                        <i class="fa fa-ticket"></i> {{ $allBookings->count() }} {{ __('Activities') }}
                                    </div>
                                @endif
                            </div> <!-- end item-right -->
                        </div> <!-- end item -->
                    </div> <!-- end container -->
                </li>





                @includeIf('Coupon::frontend/booking/checkout-coupon')

                <li class="final-total d-block">
                    @php
                        // Calculate total for all bookings in cart
                        $cartTotal = isset($bookings) && $bookings->count() > 1 ? $bookings->sum('total') : $booking->total;
                        $cartPaid = isset($bookings) && $bookings->count() > 1 ? $bookings->sum('paid') : $booking->paid;
                    @endphp
                    
                    <div class="d-flex justify-content-between">
                        <div class="label"><strong>{{ __('Services Total:') }}</strong></div>
                        <div class="val orange"><strong>{!! format_money($cartTotal) !!}</strong></div>
                    </div>
                    @if ($booking->status != 'draft')
                        <div class="d-flex justify-content-between">
                            <div class="label">{{ __('Paid:') }}</div>
                            <div class="val orange">{!! format_money($cartPaid) !!}</div>
                        </div>
                        @if ($cartPaid < $cartTotal)
                            <div class="d-flex justify-content-between">
                                <div class="label">{{ __('Remain:') }}</div>
                                <div class="val orange">{!! format_money($cartTotal - $cartPaid) !!}</div>
                            </div>
                        @endif
                    @endif
                </li>
@include ('Booking::frontend/booking/checkout-deposit-amount')
</ul>

</div>
</div>
