

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
                @if ($booking->start_date)
                    <li>
                        <div class="label">{{ __('Start date:') }}</div>
                        <div class="val">
                            {{ display_date($booking->start_date) }}
                        </div>
                    </li>
                    <li>
                        <div class="label">{{ __('Duration:') }}</div>
                        <div class="val">
                            {{ human_time_diff($booking->end_date, $booking->start_date) }}
                        </div>
                    </li>
                @endif
                @php $person_types = $booking->getJsonMeta('person_types')@endphp
                @if (!empty($person_types))
                    @foreach ($person_types as $type)
                        <li>
                            <div class="label">{{ $type['name_' . $lang_local] ?? __($type['name']) }}:</div>
                            <div class="val">
                                {{ $type['number'] }}
                            </div>
                        </li>
                    @endforeach
                @else
                    <li>
                        <div class="label">{{ __('Guests') }}:</div>
                        <div class="val">
                            {{ $booking->total_guests }}
                        </div>
                    </li>
                @endif

            </ul>
        </div>
        @do_action('booking.checkout.before_total_review')
        <div class="review-section total-review">
            <ul class="review-list">
                @php $person_types = $booking->getJsonMeta('person_types') @endphp
                @if (!empty($person_types))
                    @foreach ($person_types as $type)
                        <li>
                            <div class="label">{{ $type['name_' . $lang_local] ?? __($type['name']) }}:
                                {{ $type['number'] }} * {{ format_money($type['price']) }}</div>
                            <div class="val">
                                {{ format_money($type['price'] * $type['number']) }}
                            </div>
                        </li>
                    @endforeach
                @else
                    <li>
                        <div class="label">{{ __('Guests') }}: {{ $booking->total_guests }} *
                            {{ format_money($booking->getMeta('base_price')) }}</div>
                        <div class="val">
                            {{ format_money($booking->getMeta('base_price') * $booking->total_guests) }}
                        </div>
                    </li>
                @endif
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
                                        {{ format_money($type['total'] ?? 0) }}
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
                                        - {{ format_money($type['total'] ?? 0) }}
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
                                    : {{ $booking->total_guests }} * {{ format_money($fee_price) }}
                                @endif
                            </div>
                            <div class="val">
                                @if (!empty($item['per_person']) and $item['per_person'] == 'on')
                                    {{ format_money($fee_price * $booking->total_guests) }}
                                @else
                                    {{ format_money($fee_price) }}
                                @endif
                            </div>
                        </li>
                    @endforeach
                @endif

<li>


    {{-- 
/* -------------------------------------------------------------------------- */
/*                                Ticket Style                                */
/* -------------------------------------------------------------------------- */ 
--}}

<div class="container">
    <div class="item">
        <div class="item-right">

            @if ($booking->start_date)
                @php
                    $startDate = \Carbon\Carbon::parse($booking->start_date);
                @endphp


                <h2 class="num">{{ $startDate->format('d') }}</h2>
                <p class="day">{{ $startDate->format('M') }} 20{{ $startDate->format('y') }}</p>
            @endif

            {{-- <span class="up-border"></span>
            <span class="down-border"></span> --}}
        </div> <!-- end item-right -->

        <div class="item-left">
            <div class="barcode">
                @for ($i = 0; $i < 20; $i++) <!-- You can adjust the number of lines here -->
                    <div class="line"></div>
                @endfor
            </div>
            
            {{-- <p class="event">Music Event</p> --}}
            <h2 class="title">{!! clean($service_translation->title) !!}</h2>

            @if ($booking->start_date)
                <div class="sce">
                    <div class="icon">
                        <i class="fa fa-table"></i>
                    </div>




                    <p> {{ \Carbon\Carbon::parse($booking->start_date)->isoFormat('dddd Do YYYY') }}</p>



                </div>
                <div class="fix"></div>
                <div class="sce">
                    <div class="row">

                        <div class="col-5">

                            <div class="icon">
                                <i class="fa fa-clock-o"></i>
                            </div>
                            <p> {{ human_time_diff($booking->end_date, $booking->start_date) }} </p>

                        </div>
                        <div class="col-5">
                            <div class="icon">
                                <i class="fa fa-map-marker "></i>
                            </div>
                            @if ($service_translation->address)
                                <p> {{ $service_translation->address }}</p>
                            @endif
                        </div>



                    </div>

                </div>
            @endif


            {{-- <div class="fix"></div> --}}
            {{-- <div class="loc">
                <div class="icon">
                    <i class="fa fa-map-marker"></i>
                </div>
                @if ($service_translation->address)
                    <p> {{ $service_translation->address }}</p>
                @endif
            </div> --}}
            <div class="fix"></div>
            <div class="loc">
                <div class="icon">
                    <i class="fa fa-users"></i>
                </div>
                @php $person_types = $booking->getJsonMeta('person_types') @endphp
                @if (!empty($person_types))
                    @php
                        $count = count($person_types);
                        $text = '';
                    @endphp
                    @foreach ($person_types as $index => $type)
                        @php
                            // Build the label text
                            $person_type =
                                $type['number'] . ' ' . ($type['name_' . $lang_local] ?? __($type['name']));

                            // Append to the main text string
                            if ($index < $count - 1) {
                                // If not the last item, append with a comma or "and"
                                $text .= $person_type . ($index == $count - 2 ? ' and ' : ', ');
                            } else {
                                // If the last item
                                $text .= $person_type;
                            }
                        @endphp
                    @endforeach
                    <p>{{ $text }}</p>
                @else
                    <p> {{ $booking->total_guests }} {{ __('Guests') }}</p>


                @endif
            </div>
            @php $extra_price = $booking->getJsonMeta('extra_price') @endphp
            @if (!empty($extra_price))

                <div class="label-title"><strong>{{ __('Extra Prices:') }}</strong></div>

                <li class="no-flex">
                    <ul>
                        @foreach ($extra_price as $type)
                            <li>
                                <div class="label">
                                    {{ $type['name_' . $lang_local] ?? __($type['name']) }}:</div>
                                <div class="val">
                                    {{ format_money($type['total'] ?? 0) }}
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </li>
            @endif
            <div class="fix"></div>
            <div class="loc">

                <strong>

                    <p>{{ __('Total:') }} {{ format_money($booking->total) }}</p>

                </strong>
            </div>

        </div> <!-- end item -->
    </div>
</div>




</li>





                @includeIf('Coupon::frontend/booking/checkout-coupon')







                <li class="final-total d-block">
                    <div class="d-flex justify-content-between">
                        <div class="label">{{ __('Total:') }}</div>
                        <div class="val">{{ format_money($booking->total) }}</div>
                    </div>
                    @if ($booking->status != 'draft')
                        <div class="d-flex justify-content-between">
                            <div class="label">{{ __('Paid:') }}</div>
                            <div class="val">{{ format_money($booking->paid) }}</div>
                        </div>
                        @if ($booking->paid < $booking->total)
                            <div class="d-flex justify-content-between">
                                <div class="label">{{ __('Remain:') }}</div>
                                <div class="val">{{ format_money($booking->total - $booking->paid) }}</div>
                            </div>
                        @endif
                    @endif
                </li>
                @include ('Booking::frontend/booking/checkout-deposit-amount')
            </ul>
        
        </div>
    </div>
