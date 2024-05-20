@extends('layouts.app')
@push('css')
    <link href="{{ asset('module/booking/css/checkout.css?_ver='.config('app.asset_version')) }}" rel="stylesheet">
@endpush
@section('content')
<style>
    

    .container .item::before {
        content: ''; /* Required for :before and :after elements to show */
        position: absolute; /* Position absolutely relative to the parent .item */
        top: 0;
        left: 0;
        width: 100%; /* Match the width of the parent */
        height: 100%; /* Match the height of the parent */
        background: #00000024; /* Semi-transparent black background */
        transform: rotate(-5deg); /* Rotate the background */
        z-index: -1; /* Position behind the content */
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1), 0 6px 20px rgba(0, 0, 0, 0.19); /* Adds shadow */
        border-radius: 20px; /* Adds rounded corners */
    
    
    }
    
    .container .item::after {
        content: ''; /* Required for :before and :after elements to show */
        position: absolute; /* Position absolutely relative to the parent .item */
        top: 0;
        left: 0;
        width: 100%; /* Match the width of the parent */
        height: 100%; /* Match the height of the parent */
        background: #00000030; /* Semi-transparent black background */
        transform: rotate(10deg); /* Rotate the background */
        z-index: -1; /* Position behind the content */
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1), 0 6px 20px rgba(0, 0, 0, 0.19); /* Adds shadow */
        border-radius: 20px; /* Adds rounded corners */
    
    
    }
    
    .container .item {
        position: relative; /* Needed to position the pseudo-element correctly */
        /* overflow: hidden; Ensures nothing spills out of the item container */
        width: 100%;
        float: left;
        padding: 0 20px;
        background: #DDD;
        margin: 73px 0;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1), 0 6px 20px rgba(0, 0, 0, 0.19); /* Adds shadow */
        border-radius: 20px; /* Adds rounded corners */
    
    
    }
    
    
    .container .item-right, .container .item-left {
        float: left;
        padding: 20px
    }
    
    .container .item-right {
        padding: 84px 26px;
        margin-right: 20px;
        width: 25%;
        position: relative;
        height: 286px;
        background: #DDD; /* Ensure background is here for visibility */
    
    }
    
    
    .container .item-right .up-border, .container .item-right .down-border {
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
    
    .container .item-right .day, .container .item-left .event {
        color: #555;
        font-size: 20px;
        margin-bottom: 9px;
    }
    
    .container .item-right .day {
        text-align: center;
        font-size: 25px;
    }
    
    .container .item-left {
        width: 71%;
        padding: 34px 0px 19px 46px;
        border-left: 3px dotted #999;
    }
    
    .container .item-left .title {
        color: #111;
        font-size: 34px;
        margin-bottom: 12px
    }
    
    .container .item-left .sce {
        margin-top: 5px;
        display: block
    }
    
    .container .item-left .sce .icon, .container .item-left .sce p,
    .container .item-left .loc .icon, .container .item-left .loc p {
        float: left;
        word-spacing: 5px;
        letter-spacing: 1px;
        color: #888;
        margin-bottom: 10px;
    }
    
    .container .item-left .sce .icon, .container .item-left .loc .icon {
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
    
    .container .item .tickets, .booked, .cancel {
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
    
    <div class="bravo-booking-page padding-content" >
        <div class="container">
            <div id="bravo-checkout-page" >
                <div class="row">
                    <div class="col-md-5">
                        <h3 class="form-title">{{__('Booking Submssion')}}</h3>
                         <div class="booking-form">
                             @include ($service->checkout_form_file ?? 'Booking::frontend/booking/checkout-form')
                             @if(!empty($token = request()->input('token')))
                                 <input type="hidden" name="token" value="{{$token}}">
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
        jQuery(function () {
            $.ajax({
                'url': bookingCore.url + '{{$is_api ? '/api' : ''}}/booking/{{$booking->code}}/check-status',
                'cache': false,
                'type': 'GET',
                success: function (data) {
                    if (data.redirect !== undefined && data.redirect) {
                        window.location.href = data.redirect
                    }
                }
            });
        })

        $('.deposit_amount').on('change', function(){
            checkPaynow();
        });

        $('input[type=radio][name=how_to_pay]').on('change', function(){
            checkPaynow();
        });

        function checkPaynow(){
            var credit_input = $('.deposit_amount').val();
            var how_to_pay = $("input[name=how_to_pay]:checked").val();
            var convert_to_money = credit_input * {{ setting_item('wallet_credit_exchange_rate',1)}};

            if(how_to_pay == 'full'){
                var pay_now_need_pay = parseFloat({{floatval($booking->total)}}) - convert_to_money;
            }else{
                var pay_now_need_pay = parseFloat({{floatval($booking->deposit == null ? $booking->total : $booking->deposit)}}) - convert_to_money;
            }

            if(pay_now_need_pay < 0){
                pay_now_need_pay = 0;
            }
            $('.convert_pay_now').html(bravo_format_money(pay_now_need_pay));
            $('.convert_deposit_amount').html(bravo_format_money(convert_to_money));
        }


        jQuery(function () {
            $(".bravo_apply_coupon").click(function () {
                var parent = $(this).closest('.section-coupon-form');
                parent.find(".group-form .fa-spin").removeClass("d-none");
                parent.find(".message").html('');
                $.ajax({
                    'url': bookingCore.url + '/booking/{{$booking->code}}/apply-coupon',
                    'data': parent.find('input,textarea,select').serialize(),
                    'cache': false,
                    'method':"post",
                    success: function (res) {
                        parent.find(".group-form .fa-spin").addClass("d-none");
                        if (res.reload !== undefined) {
                            window.location.reload();
                        }
                        if(res.message && res.status === 1)
                        {
                            parent.find('.message').html('<div class="alert alert-success">' + res.message+ '</div>');
                        }
                        if(res.message && res.status === 0)
                        {
                            parent.find('.message').html('<div class="alert alert-danger">' + res.message+ '</div>');
                        }
                    }
                });
            });
            $(".bravo_remove_coupon").click(function (e) {
                e.preventDefault();
                var parent = $(this).closest('.section-coupon-form');
                var parentItem = $(this).closest('.item');
                parentItem.find(".fa-spin").removeClass("d-none");
                $.ajax({
                    'url': bookingCore.url + '/booking/{{$booking->code}}/remove-coupon',
                    'data': {
                        coupon_code:$(this).attr('data-code')
                    },
                    'cache': false,
                    'method':"post",
                    success: function (res) {
                        parentItem.find(".fa-spin").addClass("d-none");
                        if (res.reload !== undefined) {
                            window.location.reload();
                        }
                        if(res.message && res.status === 0)
                        {
                            parent.find('.message').html('<div class="alert alert-danger">' + res.message+ '</div>');
                        }
                    }
                });
            });
        })
    </script>
@endpush
