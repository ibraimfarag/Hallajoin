@extends('layouts.app')
@push('css')
    <link href="{{ asset('module/booking/css/checkout.css?_ver=' . config('app.asset_version')) }}" rel="stylesheet">
@endpush
@section('content')
    <style>
        .select-box-checkout {
            position: relative;

            width: 13rem !important;
            /* margin: 7rem auto; */
        }

        .select-box-checkout input {
            width: 100%;
            padding: 1rem .6rem;
            font-size: 14px;

            border: .1rem solid transparent;
            outline: none;
        }

        input[type="tel"] {
            border-radius: 0 .5rem .5rem 0;
        }

        .select-box-checkout input:focus {
            border: .1rem solid var(--primary);
        }

        .selected-option-checkout {
            background-color: #eee;
            border-radius: .5rem;
            overflow: hidden;

            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .selected-option-checkout div {
            position: relative;
            min-width: 4rem;
            padding: 0 0.8rem 0 .5rem;
            text-align: left;
            cursor: pointer;
        }

        .selected-option-checkout div::after {
            position: absolute;
            content: "";
            right: .8rem;
            top: 50%;
            transform: translateY(-50%) rotate(45deg);

            width: .8rem;
            height: .8rem;
            border-right: .12rem solid var(--primary);
            border-bottom: .12rem solid var(--primary);

            transition: .2s;
        }

        .selected-option-checkout div.active::after {
            transform: translateY(-50%) rotate(225deg);
        }

        .select-box-checkout .country-list {
            position: absolute;
            top: 4rem;

            width: 100%;
            background-color: #fff;
            border-radius: .5rem;
            z-index: 99999;
            display: none;
        }

        .select-box-checkout .options.active {
            display: block;
        }

        .select-box-checkout .options::before {
            position: absolute;
            content: "";
            left: 1rem;
            top: -1.2rem;

            width: 0;
            height: 0;
            border: .6rem solid transparent;
            border-bottom-color: var(--primary);
        }

        input.country-search {
            /* background-color: var(--primary); */
            color: #fff;
            border-radius: .5rem .5rem 0 0;
            padding: 1.4rem 1rem;
        }

        .select-box-checkout ol {
            list-style: none;
            max-height: 23rem;
            overflow: overlay;
        }

        .select-box-checkout ol::-webkit-scrollbar {
            width: 0.6rem;
        }

        .select-box-checkout ol::-webkit-scrollbar-thumb {
            width: 0.4rem;
            height: 3rem;
            background-color: #ccc;
            border-radius: .4rem;
        }

        .select-box-checkout ol li {
            padding: 1rem;
            display: flex;
            justify-content: space-between;
            cursor: pointer;
        }

        .select-box-checkout ol li.hide {
            display: none;
        }

        .select-box-checkout ol li:not(:last-child) {
            border-bottom: .1rem solid #eee;
        }

        .select-box-checkout ol li:hover {
            background-color: lightcyan;
        }

        .select-box-checkout ol li .country-name {
            margin-left: .4rem;
        }

        /* .container .item::before {
                    content: '';
                    position: absolute;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    background: #00000024;
                    transform: rotate(-5deg);
                    z-index: -1;
                    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1), 0 6px 20px rgba(0, 0, 0, 0.19);
                    border-radius: 20px;
                
                
                }
                
                .container .item::after {
                    content: '';
                    position: absolute;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    background: #00000030;
                    transform: rotate(10deg);
                    z-index: -1;
                    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1), 0 6px 20px rgba(0, 0, 0, 0.19);
                    border-radius: 20px;
                
                
                } */





        .container .item {
            position: relative;
            /* Needed to position the pseudo-elements correctly */
            width: 100%;
            float: left;
            padding: 0 20px;
            background: #ffffff;
            margin: 0 0;


            background-image: url('/images/ticket-2.png');
            /* Path to your background image */
            background-size: cover;
            /* Cover the entire container */
            background-position: center;
            /* Center the image */
            background-repeat: no-repeat;
            /* Prevent image repetition */


            overflow: hidden;
            /* Ensures nothing spills out of the item container */
        }



        .container .item-right,
        .container .item-left {
            float: left;
            padding: 20px
        }

        .container .item-right {
            padding: 49px 10px;
            margin-right: 20px;
            width: 35%;
            position: relative;
            height: 286px;
            /* background: #DDD; Ensure background is here for visibility */
            display: flex;
            flex-direction: column;
        }

        .fix-r {
            flex-grow: 1;
            /* This will push .loc to the bottom */
        }

        .loc-price {
            text-align: right;
        }

        .container .item-right .up-border,
        .container .item-right .down-border {
            padding: 14px 15px;
            background-color: #fff;
            border-radius: 50%;
            position: absolute
        }

        .container .item-right .up-border {
            top: -8px;
            right: -35px;
        }

        .container .item-right .down-border {
            bottom: -13px;
            right: -35px;
        }

        .container .item-right .num {
            font-size: 60px;
            text-align: center;
            color: #111
        }

        .container .item-right .loc-price span {

            color: #111
        }

        .container .item-right .day,
        .container .item-left .event {
            color: #555;
            font-size: 20px;
            margin-bottom: 9px;
        }

        .container .item-right .day {
            text-align: center;
            font-size: 25px;
        }

        .container .item-left {
            width: 60%;
            padding: 48px 0px 19px 46px;
            /* border-right: 3px dotted #999; */
            height: 250px;

            display: flex;
            flex-direction: column;
        }


        .container .item-left::before,
        .container .item-left::after {
            content: '';
            position: absolute;
            width: 50px;
            /* Diameter of the circle */
            height: 50px;
            /* Diameter of the circle */
            background-color: white;
            /* Matches the background of the container */
            border-radius: 50%;
            /* Creates the circle */
            z-index: 1;
        }

        .container .item-left::before {
            top: -30px;
            left: 65%;
            transform: translateX(-50%);
        }

        .container .item-left::after {
            bottom: -30px;
            /* Half of the circle's diameter to align it properly */
            left: 65%;
            /* Center the circle horizontally */
            transform: translateX(-50%);
        }

        .container .Tear {
            position: absolute;
            top: 50%;
            /* Center vertically */
            left: 58%;
            /* Center horizontally */
            transform: translate(-50%, -50%) rotate(90deg);
            /* Center and rotate 90 degrees */
            /* background-color: white; Ensure background color matches your design */
            padding: 0 10px;
            /* Add some padding around the label */
            z-index: 2;
            /* Ensure the label is above the circles and dotted line */
        }


        /* Add dotted lines to the left and right of the .Tear element */
        .container .Tear::before,
        .container .Tear::after {
            content: '';
            position: absolute;
            width: 85px;
            /* Length of the dotted line */
            height: 3px;
            /* Thickness of the dotted line */
            /* background-color: white; Background color of the line */
            /* border: 3px dotted #999; Dotted line style */
            border-top: 3px dotted #e7e7e7;
            /* Dotted line style */

            z-index: 1;
        }

        .container .Tear::before {
            top: 45%;
            left: -80px;
            /* Position the line to the left */
            transform: translateY(-50%);
            /* Center vertically */
        }

        .container .Tear::after {
            top: 55%;
            right: -80px;
            /* Position the line to the right */
            transform: translateY(-50%);
            /* Center vertically */
        }




        .container .item-left .title {
            font-size: 18px;
            margin-bottom: 12px
        }

        .container .item-left .sce {
            margin-top: 5px;
            display: block
        }

        .container .item-left .sce .icon,
        .container .item-left .sce p,
        .container .item-left .loc .icon,
        .container .item-left .loc p {
            float: left;
            word-spacing: 5px;
            letter-spacing: 1px;
            color: #161616;
            margin-bottom: 10px;
        }

        .container .item-left .sce .icon,
        .container .item-left .loc .icon {
            margin-right: 10px;
            /* font-size: 20px; */
            color: #666
        }

        .container .item-left .loc {
            display: block
        }

        .fix {
            clear: both
        }

        .container .item .tickets,
        .booked,
        .cancel {
            color: #fff;
            padding: 6px 14px;
            float: right;
            margin-top: 10px;
            font-size: 18px;
            border: none;
            cursor: pointer
        }

        .container .item .tickets {
            background: #777
        }

        .container .item .booked {
            background: #3D71E9
        }

        .container .item .cancel {
            background: #DF5454
        }

        .linethrough {
            text-decoration: line-through
        }

        @media only screen and (max-width: 1150px) {
            .container .item {
                width: 100%;
                margin-right: 20px
            }

            div.container {
                margin: 0 20px auto
            }
        }
    </style>

    <div class="bravo-booking-page padding-content">
        <div class="container">
            <div id="bravo-checkout-page">
                <div class="row">
                    <div class="col-md-5">
                        <h3 class="form-title">{{ __('Booking Submssion') }}</h3>
                        <div class="booking-form">
                            @include ($service->checkout_form_file ?? 'Booking::frontend/booking/checkout-form')
                            @if (!empty(($token = request()->input('token'))))
                                <input type="hidden" name="token" value="{{ $token }}">
                            @endif
                        </div>
                    </div>
                    <div class="col-md-7">
                        <div class="booking-detail booking-form">
                            @include ($service->checkout_booking_detail_file ?? '')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script src="{{ asset('module/booking/js/checkout.js') }}"></script>
    <script type="text/javascript">
        jQuery(function() {
            $.ajax({
                'url': bookingCore.url +
                    '{{ $is_api ? '/api' : '' }}/booking/{{ $booking->code }}/check-status',
                'cache': false,
                'type': 'GET',
                success: function(data) {
                    if (data.redirect !== undefined && data.redirect) {
                        window.location.href = data.redirect
                    }
                }
            });
        })

        $('.deposit_amount').on('change', function() {
            checkPaynow();
        });

        $('input[type=radio][name=how_to_pay]').on('change', function() {
            checkPaynow();
        });

        function checkPaynow() {
            var credit_input = $('.deposit_amount').val();
            var how_to_pay = $("input[name=how_to_pay]:checked").val();
            var convert_to_money = credit_input * {{ setting_item('wallet_credit_exchange_rate', 1) }};

            if (how_to_pay == 'full') {
                var pay_now_need_pay = parseFloat({{ floatval($booking->total) }}) - convert_to_money;
            } else {
                var pay_now_need_pay = parseFloat(
                    {{ floatval($booking->deposit == null ? $booking->total : $booking->deposit) }}) - convert_to_money;
            }

            if (pay_now_need_pay < 0) {
                pay_now_need_pay = 0;
            }
            $('.convert_pay_now').html(bravo_format_money(pay_now_need_pay));
            $('.convert_deposit_amount').html(bravo_format_money(convert_to_money));
        }


        jQuery(function() {
            $(".bravo_apply_coupon").click(function() {
                var parent = $(this).closest('.section-coupon-form');
                parent.find(".group-form .fa-spin").removeClass("d-none");
                parent.find(".message").html('');
                $.ajax({
                    'url': bookingCore.url + '/booking/{{ $booking->code }}/apply-coupon',
                    'data': parent.find('input,textarea,select').serialize(),
                    'cache': false,
                    'method': "post",
                    success: function(res) {
                        parent.find(".group-form .fa-spin").addClass("d-none");
                        if (res.reload !== undefined) {
                            window.location.reload();
                        }
                        if (res.message && res.status === 1) {
                            parent.find('.message').html('<div class="alert alert-success">' +
                                res.message + '</div>');
                        }
                        if (res.message && res.status === 0) {
                            parent.find('.message').html('<div class="alert alert-danger">' +
                                res.message + '</div>');
                        }
                    }
                });
            });
            $(".bravo_remove_coupon").click(function(e) {
                e.preventDefault();
                var parent = $(this).closest('.section-coupon-form');
                var parentItem = $(this).closest('.item');
                parentItem.find(".fa-spin").removeClass("d-none");
                $.ajax({
                    'url': bookingCore.url + '/booking/{{ $booking->code }}/remove-coupon',
                    'data': {
                        coupon_code: $(this).attr('data-code')
                    },
                    'cache': false,
                    'method': "post",
                    success: function(res) {
                        parentItem.find(".fa-spin").addClass("d-none");
                        if (res.reload !== undefined) {
                            window.location.reload();
                        }
                        if (res.message && res.status === 0) {
                            parent.find('.message').html('<div class="alert alert-danger">' +
                                res.message + '</div>');
                        }
                    }
                });
            });
        })
    </script>



  

@endpush
