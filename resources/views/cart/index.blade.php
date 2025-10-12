@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="page-template-content">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8">
                        <h1 class="page-title">{{__('Shopping Cart')}}</h1>

                        {{-- Success/Error Messages --}}
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif

                        @if(!Auth::check())
                            <div class="empty-cart text-center py-5">
                                <i class="fa fa-shopping-cart fa-5x text-muted mb-3"></i>
                                <h3>{{__('Please login to view your cart')}}</h3>
                                <p>{{__('You need to be logged in to add items to cart and view your cart contents.')}}</p>
                                <a href="{{ route('login') }}" class="btn btn-primary">{{__('Login')}}</a>
                                <a href="{{ route('auth.register') }}"
                                    class="btn btn-outline-primary ml-2">{{__('Register')}}</a>
                            </div>
                        @elseif(isset($cart) && is_object($cart) && isset($cart->items) && $cart->items->count() > 0)
                            <div class="cart-items">
                                @foreach($cart->items as $item)
                                    @php
                                        $service = $item->service;
                                        $bookingData = $item->booking_data ?? [];
                                        $personTypes = $bookingData['person_types'] ?? [];
                                        $startDate = isset($bookingData['start_date']) ? \Carbon\Carbon::parse($bookingData['start_date']) : null;
                                        $endDate = isset($bookingData['end_date']) ? \Carbon\Carbon::parse($bookingData['end_date']) : null;
                                    @endphp

                                    <div class="cart-item-enhanced" data-item-id="{{ $item->id }}">
                                        <div class="row">
                                            <!-- Tour Image -->
                                            <div class="col-md-3">
                                                <a href="{{ $service ? $service->getDetailUrl() : '#' }}" class="tour-link">
                                                    <div class="tour-image-container">
                                                        @if($service && $service->image_id)
                                                            <img src="{{ get_file_url($service->image_id, 'medium') }}"
                                                                alt="{{ $item->service_title }}" class="tour-image">
                                                        @else
                                                            <div class="no-image-placeholder">
                                                                <i class="fa fa-image fa-2x text-muted"></i>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </a>
                                            </div>

                                            <!-- Tour Details -->
                                            <div class="col-md-6">
                                                <div class="tour-details">
                                                    <h5 class="tour-title">
                                                        <a href="{{ $service ? $service->getDetailUrl() : '#' }}"
                                                            class="tour-title-link">
                                                            {{ $item->service_title }}
                                                        </a>
                                                    </h5>

                                                    <!-- Category -->
                                                    @if($service && $service->category_tour)
                                                        <div class="tour-category">
                                                            <i class="fa fa-tag"></i>
                                                            <span>{{ $service->category_tour->name ?? $service->category_tour->title ?? 'Tour' }}</span>
                                                        </div>
                                                    @endif

                                                    <!-- Date and Time -->
                                                    @if($startDate)
                                                        <div class="tour-datetime">
                                                            <i class="fa fa-calendar"></i>
                                                            <span>
                                                                {{ $startDate->format('D, M j') }}
                                                                @if($startDate && $endDate && !$startDate->isSameDay($endDate))
                                                                    - {{ $endDate->format('D, M j') }}
                                                                @endif
                                                                @if($startDate->format('H:i') !== '00:00' || ($endDate && $endDate->format('H:i') !== '00:00'))
                                                                    • {{ $startDate->format('h:i A') }}
                                                                    @if($endDate && !$startDate->isSameDay($endDate))
                                                                        - {{ $endDate->format('h:i A') }}
                                                                    @elseif($endDate && $endDate->format('H:i') !== $startDate->format('H:i'))
                                                                        - {{ $endDate->format('h:i A') }}
                                                                    @endif
                                                                @endif
                                                            </span>
                                                        </div>
                                                    @endif

                                                    <!-- Person Types -->
                                                    @if(!empty($personTypes))
                                                        <div class="person-types">
                                                            <i class="fa fa-users"></i>
                                                            <span>
                                                                @foreach($personTypes as $index => $personType)
                                                                    @if($personType['number'] > 0)
                                                                        {{ $personType['number'] }}
                                                                        {{ $personType['name'] }}@if($index < count(array_filter($personTypes, function ($p) {
                                                                            return $p['number'] > 0; })) - 1),
                                                                        @endif
                                                                    @endif
                                                                @endforeach
                                                            </span>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>

                                            <!-- Price and Actions -->
                                            <div class="col-md-3">
                                                <div class="price-actions">


                                                    <!-- Total Price -->
                                                    <div class="total-section">
                                                        <div class="item-total">
                                                            <strong>{{ number_format($item->total_price, 2) }}{!! get_current_currency_svg() !!}</strong>
                                                        </div>
                                                    </div>

                                                    <!-- Remove Button -->
                                                    <div class="remove-section">
                                                        <button class="btn btn-sm btn-outline-danger remove-item"
                                                            data-item-id="{{ $item->id }}" title="{{__('Remove item')}}">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr class="cart-divider">
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

                    @if(Auth::check() && isset($cart) && is_object($cart) && isset($cart->items) && $cart->items->count() > 0)
                        <div class="col-lg-4">
                            <div class="cart-summary card">
                                <div class="card-header">
                                    <h4>{{__('Order Summary')}}</h4>
                                </div>
                                <div class="card-body">
                                    <div class="summary-line">
                                        <span>{{__('Items')}} ({{ $cart->items->count() }})</span>
                                        <span
                                            class="cart-subtotal">{{ number_format($cart->total_amount ?? 0, 2) }}{!! get_current_currency_svg() !!}</span>
                                    </div>
                                    <hr>
                                    <div class="summary-line total">
                                        <strong>
                                            <span>{{__('Total')}}</span>
                                            <span
                                                class="cart-total">{{ number_format($cart->total_amount ?? 0, 2) }}{!! get_current_currency_svg() !!}</span>
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
        /* Enhanced Cart Item Styling */
        .cart-item-enhanced {
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            transition: box-shadow 0.3s ease;
        }

        .cart-item-enhanced:hover {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }

        .cart-divider {
            margin: 0;
            border: none;
        }

        /* Tour Image */
        .tour-link {
            display: block;
            text-decoration: none;
            transition: opacity 0.3s ease;
        }

        .tour-link:hover {
            opacity: 0.8;
            text-decoration: none;
        }

        .tour-image-container {
            position: relative;
            width: 100%;
            height: 120px;
            border-radius: 8px;
            overflow: hidden;
            background: #f8f9fa;
            transition: transform 0.3s ease;
        }

        .tour-link:hover .tour-image-container {
            transform: scale(1.02);
        }

        .tour-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 8px;
        }

        .no-image-placeholder {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f8f9fa;
            border: 2px dashed #dee2e6;
            border-radius: 8px;
        }

        /* Tour Details */
        .tour-details {
            padding-left: 15px;
        }

        .tour-title {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 10px;
            color: #333;
            line-height: 1.4;
        }

        .tour-title-link {
            color: #333;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .tour-title-link:hover {
            color: #007bff;
            text-decoration: none;
        }

        .tour-category {
            display: flex;
            align-items: center;
            margin-bottom: 8px;
            color: #6c757d;
            font-size: 0.9rem;
        }

        .tour-category i {
            margin-right: 6px;
            color: #007bff;
        }

        .tour-datetime {
            display: flex;
            align-items: center;
            margin-bottom: 8px;
            color: #495057;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .tour-datetime i {
            margin-right: 6px;
            color: #28a745;
        }

        .person-types {
            display: flex;
            align-items: center;
            color: #6c757d;
            font-size: 0.9rem;
        }

        .person-types i {
            margin-right: 6px;
            color: #17a2b8;
        }

        /* Price and Actions */
        .price-actions {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            height: 100%;
            justify-content: space-between;
        }

        .price-section {
            text-align: right;
            margin-bottom: 15px;
        }

        .item-price {
            font-size: 1.1rem;
            font-weight: 600;
            color: #333;
        }

        .item-price svg {
            width: 16px;
            height: 16px;
            margin-left: 4px;
            vertical-align: middle;
        }

        .total-section {
            margin-bottom: 15px;
            text-align: right;
        }

        .item-total {
            font-size: 1.2rem;
            font-weight: 700;
            color: #333;
        }

        .item-total svg {
            width: 18px;
            height: 18px;
            margin-left: 4px;
            vertical-align: middle;
        }

        .remove-section {
            text-align: right;
        }

        .remove-item {
            width: 35px;
            height: 35px;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Summary styling updates */
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

        /* Currency symbol styling for summary */
        .cart-subtotal svg,
        .cart-total svg {
            width: 16px;
            height: 16px;
            margin-left: 4px;
            vertical-align: middle;
        }

        .summary-line svg {
            width: 18px;
            height: 18px;
            margin-left: 4px;
            vertical-align: middle;
        }

        /* Responsive design */
        @media (max-width: 768px) {
            .cart-item-enhanced {
                padding: 15px;
            }

            .tour-details {
                padding-left: 0;
                margin-top: 15px;
            }

            .price-actions {
                align-items: flex-start;
                margin-top: 15px;
            }

            .tour-image-container {
                height: 100px;
            }

            .quantity-controls {
                justify-content: flex-start;
            }
        }
    </style>

    <script>
            // Simple approach withou     t jQuery conflicts
            window.addEventListener('load', function() {
                console.log('Cart page loaded, setting up click handlers...');

                // Clear cart button
                var clearCartBtn = document.querySelector('.clear-cart');
                if (clearCartBtn) {
                    clearCartBtn.addEventListener('click', function(e) {
                        e.preventDefault();
                        console.log('Clear cart button clicked');

                        if (confirm('{{ __("Are you sure you want to clear your cart?") }}')) {
                            var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                            console.log('CSRF Token:', csrfToken);

                            // Create form and submit
                            var form = document.createElement('form');
                            form.method = 'POST';
                            form.action = '{{ route("cart.clear") }}';
                            form.style.display = 'none';

                            // Add CSRF token
                            var csrfInput = document.createElement('input');
                            csrfInput.type = 'hidden';
                            csrfInput.name = '_token';
                            csrfInput.value = csrfToken;
                            form.appendChild(csrfInput);

                            // Add method override for DELETE
                            var methodInput = document.createElement('input');
                            methodInput.type = 'hidden';
                            methodInput.name = '_method';
                            methodInput.value = 'DELETE';
                            form.appendChild(methodInput);

                            document.body.appendChild(form);
                            form.submit();
                        }
                    });
                    console.log('Clear cart button handler attached');
                } else {
                    console.log('Clear cart button not found');
                }

                // Remove item buttons
                var removeButtons = document.querySelectorAll('.remove-item');
                removeButtons.forEach(function(btn) {
                    btn.addEventListener('click', function(e) {
                        e.preventDefault();
                        var itemId = this.getAttribute('data-item-id');
                        console.log('Remove item clicked:', itemId);

                        if (confirm('{{ __("Are you sure you want to remove this item?") }}')) {
                            var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                            // Create form and submit
                            var form = document.createElement('form');
                            form.method = 'POST';
                            form.action = '/cart/item/' + itemId;
                            form.style.display = 'none';

                            // Add CSRF token
                            var csrfInput = document.createElement('input');
                            csrfInput.type = 'hidden';
                            csrfInput.name = '_token';
                            csrfInput.value = csrfToken;
                            form.appendChild(csrfInput);

                            // Add method override for DELETE
                            var methodInput = document.createElement('input');
                            methodInput.type = 'hidden';
                            methodInput.name = '_method';
                            methodInput.value = 'DELETE';
                            form.appendChild(methodInput);

                            document.body.appendChild(form);
                            form.submit();
                        }
                    });
                });
                console.log('Remove item handlers attached to', removeButtons.length, 'buttons');

                // Checkout button
                var checkoutBtn = document.querySelector('.checkout-btn');
                if (checkoutBtn) {
                    checkoutBtn.addEventListener('click', function(e) {
                        e.preventDefault();
                        alert('{{ __("Checkout functionality will be implemented here") }}');
                    });
                }
            });
        </script>
@endsection