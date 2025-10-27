@extends('admin.layouts.app')

@section('content')
    <link rel="stylesheet" href="{{ asset('module/report/css/create-order.css') }}">

    <input type="hidden" id="csrf_token" value="{{ csrf_token() }}">

    <div class="create-order-container">
        <!-- Page Header -->
        <div class="page-header">
            <div class="header-left">
                <a href="{{ route('report.admin.booking') }}" class="btn-back">
                    <i class="fa fa-arrow-left"></i> {{ __('Back to Sales') }}
                </a>
                <h1 class="page-title">{{ __('Create New Order') }}</h1>
            </div>
        </div>

        @include('admin.message')

        <div class="create-order-layout">
            <!-- Left Side: Activities Search & Selection -->
            <div class="activities-section">
                <div class="section-card">
                    <div class="card-header">
                        <h3><i class="fa fa-search"></i> {{ __('Search Activities') }}</h3>
                    </div>
                    <div class="card-body">
                        <!-- Search Input -->
                        <div class="search-box">
                            <input type="text" id="activitySearch" class="search-input"
                                placeholder="{{ __('Search for tours, hotels, activities...') }}">
                            <i class="fa fa-search search-icon"></i>
                        </div>

                        <!-- Search Results -->
                        <div id="searchResults" class="search-results">
                            <div class="no-results">
                                <i class="fa fa-search"></i>
                                <p>{{ __('Start typing to search for activities') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Activity Details Modal (Shown when activity is selected) -->
                <div id="activityDetailsModal" class="activity-details-modal" style="display: none;">
                    <div class="modal-overlay" onclick="closeActivityDetails()"></div>
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3 id="modalActivityTitle">{{ __('Activity Details') }}</h3>
                            <button class="btn-close" onclick="closeActivityDetails()">
                                <i class="fa fa-times"></i>
                            </button>
                        </div>
                        <div class="modal-body">
                            <!-- Activity Image -->
                            <div class="activity-image" id="modalActivityImage"></div>

                            <!-- Date Selection -->
                            <div class="form-group">
                                <label>{{ __('Select Date') }}</label>
                                <input type="date" id="selectedDate" class="form-control">
                            </div>

                            <!-- Time Selection -->
                            <div class="form-group">
                                <label>{{ __('Select Time') }}</label>
                                <div id="timeSelectionContainer">
                                    <div class="loading-times">
                                        <i class="fa fa-clock-o"></i> {{ __('Select a date first') }}
                                    </div>
                                </div>
                            </div>

                            <!-- Person Types -->
                            <div class="form-group">
                                <label>{{ __('Guests') }}</label>
                                <div id="personTypesContainer"></div>
                            </div>

                            <!-- Total Price -->
                            <div class="total-price-box">
                                <div class="price-label">{{ __('Total Price') }}:</div>
                                <div class="price-value">
                                    <span id="modalTotalPrice">0</span>
                                    <span class="currency">{!! get_current_currency_svg() !!}</span>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-secondary" onclick="closeActivityDetails()">
                                {{ __('Cancel') }}
                            </button>
                            <button class="btn btn-primary" onclick="addToCart()">
                                <i class="fa fa-plus"></i> {{ __('Add to Cart') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side: Customer & Cart -->
            <div class="cart-section">
                <!-- Customer Search -->
                <div class="section-card">
                    <div class="card-header">
                        <h3><i class="fa fa-user"></i> {{ __('Customer') }}</h3>
                    </div>
                    <div class="card-body">
                        <div class="customer-search-box">
                            <input type="text" id="customerPhone" class="form-control"
                                placeholder="{{ __('Enter phone number...') }}">
                            <button class="btn btn-primary" onclick="searchCustomer()">
                                <i class="fa fa-search"></i>
                            </button>
                        </div>

                        <!-- Customer Info (Hidden until selected) -->
                        <div id="customerInfo" class="customer-info" style="display: none;">
                            <div class="customer-avatar" id="customerAvatar"></div>
                            <div class="customer-details">
                                <div class="customer-name" id="customerName"></div>
                                <div class="customer-phone" id="customerPhoneDisplay"></div>
                                <div class="customer-email" id="customerEmail"></div>
                            </div>
                            <button class="btn-remove-item" onclick="clearCustomer()">
                                <i class="fa fa-trash"></i>
                            </button>
                        </div>

                        <!-- Wallet & Points -->
                        <div id="customerBalance" class="customer-balance" style="display: none;">
                            <div class="balance-item">
                                <i class="fa fa-wallet"></i>
                                <span>{{ __('Wallet') }}:</span>
                                <strong id="walletAmount">0 {!! get_current_currency_svg() !!}</strong>
                            </div>
                            <div class="balance-item">
                                <i class="fa fa-star"></i>
                                <span>{{ __('Points') }}:</span>
                                <strong id="pointsAmount">0</strong>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Cart -->
                <div class="section-card">
                    <div class="card-header">
                        <h3><i class="fa fa-shopping-cart"></i> {{ __('Cart') }}</h3>
                        <span class="cart-count" id="cartCount">0</span>
                    </div>
                    <div class="card-body">
                        <div id="cartItems" class="cart-items">
                            <div class="empty-cart">
                                <i class="fa fa-shopping-cart"></i>
                                <p>{{ __('Cart is empty') }}</p>
                            </div>
                        </div>

                        <!-- Cart Summary -->
                        <div id="cartSummary" class="cart-summary" style="display: none;">
                            <div class="summary-row">
                                <span>{{ __('Subtotal') }} (<span id="itemsCount">0</span>):</span>
                                <strong><span id="subtotal">0</span> {!! get_current_currency_svg() !!}</strong>
                            </div>
                            <div class="summary-row total">
                                <span>{{ __('To Be Paid') }}:</span>
                                <strong class="total-amount">
                                    <span id="totalAmount">0</span> {!! get_current_currency_svg() !!}
                                </strong>
                            </div>
                        </div>

                        <!-- Create Order Button -->
                        <button id="createOrderBtn" class="btn btn-create-order" onclick="createOrder()" disabled>
                            <i class="fa fa-check-circle"></i> {{ __('CREATE ORDER') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Confirm Order Modal -->
        <div id="confirmOrderModal" class="activity-details-modal" style="display: none;">
            <div class="modal-overlay" onclick="closeConfirmOrderModal()"></div>
            <div class="modal-content" style="max-width: 500px;">
                <div class="modal-header">
                    <h3><i class="fa fa-check-circle"></i> {{ __('Confirm Order') }}</h3>
                    <button class="btn-close" onclick="closeConfirmOrderModal()">
                        <i class="fa fa-times"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div style="text-align: center; padding: 20px 0;">
                        <i class="fa fa-shopping-cart" style="font-size: 48px; color: #059669; margin-bottom: 20px;"></i>
                        <p style="font-size: 18px; margin-bottom: 10px;">{{ __('Create order for') }}</p>
                        <h4 id="confirmCustomerName" style="color: var(--create-text-primary); margin-bottom: 20px;"></h4>

                        <div
                            style="background: var(--create-bg-tertiary); padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                            <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                                <span>{{ __('Items') }}:</span>
                                <strong id="confirmItemsCount">0</strong>
                            </div>
                            <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                                <span>{{ __('Total Guests') }}:</span>
                                <strong id="confirmTotalGuests">0</strong>
                            </div>
                            <div
                                style="display: flex; justify-content: space-between; font-size: 18px; padding-top: 10px; border-top: 2px solid var(--create-border-color);">
                                <span>{{ __('Total Amount') }}:</span>
                                <strong style="color: #059669;">
                                    <span id="confirmTotalAmount">0</span> {!! get_current_currency_svg() !!}
                                </strong>
                            </div>
                        </div>

                        <p style="color: var(--create-text-secondary); font-size: 14px;">
                            {{ __('The customer will receive a notification with payment link') }}
                        </p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" onclick="closeConfirmOrderModal()">
                        <i class="fa fa-times"></i> {{ __('Cancel') }}
                    </button>
                    <button class="btn btn-primary" onclick="confirmCreateOrder()">
                        <i class="fa fa-check"></i> {{ __('Confirm Order') }}
                    </button>
                </div>
            </div>
        </div>

        <!-- Success Order Modal -->
        <div id="successOrderModal" class="activity-details-modal" style="display: none;">
            <div class="modal-overlay"></div>
            <div class="modal-content" style="max-width: 500px;">
                <div class="modal-body">
                    <div style="text-align: center; padding: 30px 20px;">
                        <div style="width: 80px; height: 80px; border-radius: 50%; background: #059669; margin: 0 auto 20px; display: flex; align-items: center; justify-content: center;">
                            <i class="fa fa-check" style="font-size: 48px; color: white;"></i>
                        </div>
                        <h3 style="color: var(--create-text-primary); margin-bottom: 15px; font-size: 24px;">
                            {{ __('Order Created Successfully!') }}
                        </h3>
                        <p style="color: var(--create-text-secondary); font-size: 16px; margin-bottom: 10px;">
                            {{ __('The customer will receive a notification') }}
                        </p>
                       
                        <button class="btn btn-primary" onclick="redirectToBookings()" style="padding: 12px 40px; font-size: 16px;">
                            <i class="fa fa-arrow-left"></i> {{ __('Back to Sales') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Make currency SVG available to JavaScript
        window.currencySvg = `{!! str_replace(['`', '${'], ['\`', '\${'], get_current_currency_svg()) !!}`;
    </script>
    <script src="{{ asset('module/report/js/create-order.js') }}"></script>
@endsection