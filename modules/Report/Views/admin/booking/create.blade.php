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
                            <button class="btn-edit-customer" onclick="clearCustomer()">
                                <i class="fa fa-pencil"></i>
                            </button>
                        </div>

                        <!-- Wallet & Points -->
                        <div id="customerBalance" class="customer-balance" style="display: none;">
                            <div class="balance-item">
                                <i class="fa fa-wallet"></i>
                                <span>{{ __('Wallet') }}:</span>
                                <strong id="walletAmount">0 AED</strong>
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
    </div>

    <script src="{{ asset('module/report/js/create-order.js') }}"></script>
@endsection