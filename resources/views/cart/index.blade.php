@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="page-template-content">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8">
                        <h1 class="page-title">{{__('Shopping Cart')}}</h1>

                        @if(!Auth::check())
                            <div class="empty-cart text-center py-5">
                                <i class="fa fa-shopping-cart fa-5x text-muted mb-3"></i>
                                <h3>{{__('Please login to view your cart')}}</h3>
                                <p>{{__('You need to be logged in to add items to cart and view your cart contents.')}}</p>
                                <a href="{{ route('login') }}" class="btn btn-primary">{{__('Login')}}</a>
                                <a href="{{ route('auth.register') }}"
                                    class="btn btn-outline-primary ml-2">{{__('Register')}}</a>
                            </div>
                        @elseif(isset($cart->items) && $cart->items->count() > 0)
                            <div class="cart-items">
                                @foreach($cart->items as $item)
                                    <div class="cart-item" data-item-id="{{ $item->id }}">
                                        <div class="row align-items-center">
                                            <div class="col-md-6">
                                                <h5>{{ $item->service_title }}</h5>
                                                <p class="text-muted">{{ ucfirst($item->service_type) }}</p>
                                                @if($item->booking_data)
                                                    <small class="text-info">
                                                        @foreach($item->booking_data as $key => $value)
                                                            @if(!is_array($value))
                                                                {{ ucfirst($key) }}: {{ $value }}<br>
                                                            @endif
                                                        @endforeach
                                                    </small>
                                                @endif
                                            </div>
                                            <div class="col-md-2">
                                                <div class="quantity-controls">
                                                    <input type="number" class="form-control quantity-input"
                                                        value="{{ $item->quantity }}" min="1" data-item-id="{{ $item->id }}">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <span class="item-price">{{ number_format($item->price, 2) }} {!! currency_symbol() !!}</span>
                                            </div>
                                            <div class="col-md-1">
                                                <span class="item-total">{{ number_format($item->total_price, 2) }} {!! currency_symbol() !!}</span>
                                            </div>
                                            <div class="col-md-1">
                                                <button class="btn btn-sm btn-outline-danger remove-item"
                                                    data-item-id="{{ $item->id }}" 
                                                    title="{{ __('Remove item') }}">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                @endforeach
                            </div>
                        @else
                            <div class="empty-cart text-center py-5">
                                <i class="fa fa-shopping-cart fa-5x text-muted mb-3"></i>
                                <h3>{{__('Your cart is empty')}}</h3>
                                <p>{{__('Add some services to your cart to get started!')}}</p>
                                <a href="{{ url('/') }}" class="btn btn-primary">{{__('Continue Shopping')}}</a>
                            </div>
                        @endif
                    </div>

                    @if(Auth::check() && isset($cart->items) && $cart->items->count() > 0)
                        <div class="col-lg-4">
                            <div class="cart-summary card">
                                <div class="card-header">
                                    <h4>{{__('Order Summary')}}</h4>
                                </div>
                                <div class="card-body">
                                    <div class="summary-line">
                                        <span>{{__('Items')}} ({{ $cart->items->count() }})</span>
                                        <span class="cart-subtotal">{{ number_format($cart->total_amount ?? 0, 2) }} {!! currency_symbol() !!}</span>
                                    </div>
                                    <hr>
                                    <div class="summary-line total">
                                        <strong>
                                            <span>{{__('Total')}}</span>
                                            <span class="cart-total">{{ number_format($cart->total_amount ?? 0, 2) }} {!! currency_symbol() !!}</span>
                                        </strong>
                                    </div>
                                    <div class="mt-3">
                                        <button class="btn btn-success btn-block checkout-btn">
                                            {{__('Proceed to Checkout')}}
                                        </button>
                                    </div>
                                    <div class="mt-2">
                                        <button class="btn btn-outline-secondary btn-block clear-cart">
                                            {{__('Clear Cart')}}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <style>
        .cart-item {
            padding: 15px 0;
            transition: background-color 0.3s ease;
        }

        .cart-item:hover {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 15px;
            margin: 0 -15px;
        }

        .quantity-controls input {
            width: 80px;
            text-align: center;
            border-radius: 6px;
            border: 1px solid #ddd;
            transition: border-color 0.3s ease;
        }

        .quantity-controls input:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        .summary-line {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding: 5px 0;
        }

        .summary-line.total {
            font-size: 1.2em;
            border-top: 2px solid #dee2e6;
            padding-top: 15px;
            margin-top: 10px;
        }

        .empty-cart {
            min-height: 400px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .cart-summary .card {
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border: none;
            border-radius: 12px;
        }

        .cart-summary .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 12px 12px 0 0 !important;
            border: none;
        }

        .item-price, .item-total {
            font-weight: 600;
            color: #28a745;
        }

        .cart-total, .cart-subtotal {
            font-weight: 700;
            color: #007bff;
        }

        .remove-item {
            transition: all 0.3s ease;
        }

        .remove-item:hover {
            background-color: #dc3545;
            border-color: #dc3545;
            color: white;
            transform: scale(1.1);
        }

        .checkout-btn {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            border: none;
            padding: 12px;
            font-weight: 600;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .checkout-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(40, 167, 69, 0.3);
        }

        .clear-cart {
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .clear-cart:hover {
            background-color: #6c757d;
            border-color: #6c757d;
            color: white;
        }

        /* Currency symbol styling */
        .currency-symbol {
            display: inline-block;
            margin-left: 4px;
            vertical-align: middle;
        }

        .currency-symbol svg {
            width: 16px;
            height: 16px;
            fill: currentColor;
        }

        /* Responsive improvements */
        @media (max-width: 768px) {
            .cart-item .row > div {
                margin-bottom: 10px;
            }
            
            .cart-item .col-md-6 {
                order: 1;
            }
            
            .cart-item .col-md-2:first-of-type {
                order: 2;
            }
            
            .cart-item .col-md-2:last-of-type,
            .cart-item .col-md-1 {
                order: 3;
            }
        }
    </style>

    <script>
        // Fallback SafeDOM implementation if not loaded
        if (typeof SafeDOM === 'undefined') {
            window.SafeDOM = {
                jQuery: function (callback) {
                    function checkReady() {
                        if (document.readyState === 'complete' && typeof $ !== 'undefined') {
                            callback($);
                        } else {
                            setTimeout(checkReady, 50);
                        }
                    }

                    if (document.readyState === 'loading') {
                        document.addEventListener('DOMContentLoaded', checkReady);
                    } else {
                        checkReady();
                    }
                }
            };
        }

        // Use SafeDOM to ensure jQuery is loaded and DOM is ready
        SafeDOM.jQuery(function ($) {
            // Update quantity
            $('.quantity-input').on('change', function () {
                const itemId = $(this).data('item-id');
                const quantity = $(this).val();

                $.ajax({
                    url: `/cart/item/${itemId}`,
                    method: 'PUT',
                    data: {
                        quantity: quantity,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        if (response.success) {
                            $(`.cart-item[data-item-id="${itemId}"] .item-total`).html(response.total_price.toFixed(2) + ' {!! addslashes(currency_symbol()) !!}');
                            $('.cart-total, .cart-subtotal').html(response.cart_total.toFixed(2) + ' {!! addslashes(currency_symbol()) !!}');
                        }
                    }
                });
            });

            // Remove item
            $('.remove-item').on('click', function () {
                const itemId = $(this).data('item-id');

                if (confirm('{{__("Are you sure you want to remove this item?")}}')) {
                    $.ajax({
                        url: `/cart/item/${itemId}`,
                        method: 'DELETE',
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function (response) {
                            if (response.success) {
                                $(`.cart-item[data-item-id="${itemId}"]`).remove();
                                $('.cart-total, .cart-subtotal').html(response.cart_total.toFixed(2) + ' {!! addslashes(currency_symbol()) !!}');

                                if ($('.cart-item').length === 0) {
                                    location.reload();
                                }
                            }
                        }
                    });
                }
            });

            // Clear cart
            $('.clear-cart').on('click', function () {
                if (confirm('{{__("Are you sure you want to clear your cart?")}}')) {
                    $.ajax({
                        url: '{{ route("cart.clear") }}',
                        method: 'DELETE',
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function (response) {
                            if (response.success) {
                                location.reload();
                            }
                        }
                    });
                }
            });
        });
    </script>
@endsection