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
                                                <span class="item-price">{{ number_format($item->price, 2) }} AED</span>
                                            </div>
                                            <div class="col-md-1">
                                                <span class="item-total">{{ number_format($item->total_price, 2) }} AED</span>
                                            </div>
                                            <div class="col-md-1">
                                                <button class="btn btn-sm btn-outline-danger remove-item"
                                                    data-item-id="{{ $item->id }}">
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
                                        <span class="cart-subtotal">{{ number_format($cart->total_amount ?? 0, 2) }} AED</span>
                                    </div>
                                    <hr>
                                    <div class="summary-line total">
                                        <strong>
                                            <span>{{__('Total')}}</span>
                                            <span class="cart-total">{{ number_format($cart->total_amount ?? 0, 2) }} AED</span>
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
        }

        .quantity-controls input {
            width: 80px;
        }

        .summary-line {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .summary-line.total {
            font-size: 1.2em;
        }

        .empty-cart {
            min-height: 400px;
            display: flex;
            flex-direction: column;
            justify-content: center;
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
                            $(`.cart-item[data-item-id="${itemId}"] .item-total`).text(response.total_price.toFixed(2) + ' AED');
                            $('.cart-total, .cart-subtotal').text(response.cart_total.toFixed(2) + ' AED');
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
                                $('.cart-total, .cart-subtotal').text(response.cart_total.toFixed(2) + ' AED');

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