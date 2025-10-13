@php
    if (!auth()->check())
        return;
    if (!function_exists('getUserCartData')) {
        require_once app_path('Helpers/CartHelper.php');
    }
    $cart = getUserCartData();
    $cartItems = $cart['items'] ?? [];
    $totalPrice = $cart['total'] ?? 0;
@endphp
<style>
    .cart-icon-badge {
        position: absolute;
        top: 2px;
        right: 2px;
        font-size: 12px;
        min-width: 18px;
        min-height: 18px;
        padding: 2px 5px;
        border-radius: 50%;
        z-index: 2;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .dropdown-cart {
        position: relative;
    }


    .dropdown-cart .fa-angle-down {
        margin-left: 2px;
    }

    /* Cart dropdown menu: unique class to avoid conflict */
    .dropdown-cart>.cart-dropdown-menu.cart-items-list {
        position: absolute;
        left: auto;
        right: 0;
        min-width: 350px;
        max-width: 400px;
        top: 100%;
        margin-top: 0;
        z-index: 1002;
        box-shadow: 0 6px 24px rgba(0, 0, 0, 0.12);
        background: #fff;
        border-radius: 8px;
        border: 1px solid #e8e8e8;
        display: none;
        /* Hidden by default */
        padding: 0;
        max-height: 450px;
        overflow-y: auto;
    }

    /* Bootstrap compatibility */
    .dropdown-cart.show>.cart-dropdown-menu,
    .dropdown-cart.open>.cart-dropdown-menu,
    .cart-dropdown-menu.show {
        display: block !important;
    }

    .cart-dropdown-menu .dropdown-toolbar {
        background: #f8f9fa;
        padding: 15px 20px;
        border-bottom: 1px solid #e8e8e8;
        border-radius: 8px 8px 0 0;
    }

    .cart-dropdown-menu .dropdown-toolbar-title {
        margin: 0;
        font-size: 16px;
        font-weight: 600;
        color: #333;
    }

    .cart-dropdown-menu .cart-item {
        padding: 15px 20px;
        border-bottom: 1px solid #f0f0f0;
        display: flex;
        align-items: center;
    }

    .cart-dropdown-menu .cart-item:last-child {
        border-bottom: none;
    }

    .cart-dropdown-menu .cart-item .media-left img {
        border-radius: 6px;
        object-fit: cover;
        margin-right: 15px;
    }

    .cart-dropdown-menu .cart-item-title {
        font-weight: 600;
        color: #333;
        margin-bottom: 5px;
        font-size: 14px;
    }

    .cart-dropdown-menu .cart-item-qty,
    .cart-dropdown-menu .cart-item-price {
        font-size: 13px;
        color: #666;
        margin-bottom: 2px;
    }

    .cart-dropdown-menu .cart-item-price {
        font-weight: 600;
        color: #007bff;
    }

    .cart-dropdown-menu .remove-cart-item {
        color: #dc3545;
        font-size: 16px;
        padding: 5px;
        margin-left: 10px;
    }

    .cart-dropdown-menu .remove-cart-item:hover {
        color: #c82333;
        text-decoration: none;
    }

    .cart-dropdown-menu .dropdown-footer {
        background: #f8f9fa;
        border-top: 1px solid #e8e8e8;
        border-radius: 0 0 8px 8px;
        padding: 15px 20px;
    }

    .cart-dropdown-menu .cart-total-price {
        font-weight: 700;
        font-size: 16px;
        color: #0000027e;
    }

    .cart-dropdown-menu .btn {
        border-radius: 6px;
        font-weight: 600;
    }

    /* Override default dropdown-menu styles while keeping the class for JS functionality */
    .custom-cart-menu.dropdown-menu {
        /* Reset unwanted Bootstrap dropdown-menu styles */
        margin: 0;
        padding: 0;
        float: none;
        background-clip: initial;
        border: none;
        border-radius: 0;
        box-shadow: none;
        font-size: inherit;
        color: inherit;
        text-align: left;
        list-style: none;
        background-color: transparent;
    }

    /* Show dropdown when open */
    .dropdown-cart.open>.cart-dropdown-menu.cart-items-list,
    .dropdown-cart.show>.cart-dropdown-menu.cart-items-list,
    .dropdown-cart>.dropdown-menu.cart-dropdown-menu.show {
        display: block;
    }
</style>
<li class="dropdown-cart dropdown p-0" style="position: relative;">
    <a href="#" id="cartDropdownToggle" data-toggle="dropdown" class="is_login"
        style="position: relative; display: flex; align-items: center; gap: 8px;" aria-haspopup="true"
        aria-expanded="false">
        <span style="position: relative; display: inline-block;">
            <i class="fa fa-shopping-cart"></i>
            <span class="badge badge-danger orange-bg cart-icon-badge" id="cart-icon-count"
                style="top: -8px; right: -8px;">{{ count($cartItems) }}</span>
        </span>
        <span class="nav-text">{{__('Cart')}}</span>
        <i class="fa fa-angle-down"></i>
    </a>
    <ul class="cart-dropdown-menu dropdown-menu custom-cart-menu overflow-auto cart-items-list dropdown-container dropdown-menu-right dropdown-large"
        style="right: 0 !important; left: auto !important;" aria-labelledby="cartDropdownToggle">
        <div class="dropdown-toolbar">
            <h3 class="dropdown-toolbar-title">{{__('Cart')}} (<span class="cart-count">{{ count($cartItems) }}</span>)
            </h3>
        </div>
        <ul class="dropdown-list-items px-2">
            @forelse($cartItems as $item)
                <li class="cart-item">
                    <div class="media ">
                        <div class="media-left">
                            <img src="{{ $item['image_url'] ?? asset('images/default.png') }}" width="50" height="50"
                                alt="{{ $item['name'] }}">
                        </div>
                        <div class="media-body">
                            <div class="cart-item-title">{{ $item['name'] }}</div>
                            @if(!empty($item['booking_info']))
                                <div class="cart-item-booking-info text-muted" style="font-size:13px;">
                                    @foreach($item['booking_info'] as $info)
                                        <div>{{ $info }}</div>
                                    @endforeach
                                </div>
                            @endif
                            {{-- <div class="cart-item-qty">{{__('Qty')}}: {{ $item['quantity'] }}</div> --}}
                            <div class="cart-item-price">{!! format_money($item['price']) !!}</div>
                        </div>
                        <div class="media-right">
                            <a href="#" class="remove-cart-item" data-id="{{ $item['id'] }}"><i class="fa fa-trash"></i></a>
                        </div>
                    </div>
                </li>
            @empty
                <li class="cart-item text-center text-muted">{{__('Cart is empty')}}</li>
            @endforelse
        </ul>
        <div class="dropdown-footer p-2 border-top">
            <div class="d-flex justify-content-between align-items-center">
                <span class="font-weight-bold">{{__('Total')}}:</span>
                <span class="cart-total-price"
                    style="color:#0000009a; font-weight:bold; font-size:16px;">{!! format_money($totalPrice) !!}</span>
            </div>
            <a href="{{ route('cart.index') }}" class="btn btn-primary"
                style="display:block; margin: 0 40px 10px 40px; background:#007bff; color:#fff; border-radius:6px; font-weight:600; text-align:center;">
                {{__('View Cart')}}
            </a>
        </div>
    </ul>
</li>
<script>
    function updateCartIconCount() {
        fetch('/cart/count')
            .then(response => response.json())
            .then(data => {
                if (data.count !== undefined) {
                    document.getElementById('cart-icon-count').textContent = data.count;
                }
            });
    }
    document.addEventListener('DOMContentLoaded', updateCartIconCount);
    // حذف عنصر من العربة
    document.addEventListener('click', function (e) {
        // Only handle remove inside the cart dropdown
        if (e.target.closest('.remove-cart-item') && e.target.closest('.cart-dropdown-menu')) {
            e.preventDefault();
            var btn = e.target.closest('.remove-cart-item');
            var id = btn.dataset.id;
            fetch('/cart/remove', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ id: id })
            })
                .then(function (res) { return res.json(); })
                .then(function (data) {
                    // Remove the cart item from DOM
                    var cartItem = btn.closest('.cart-item');
                    if (cartItem) cartItem.remove();
                    // Update cart icon count if provided
                    if (data.cart_count !== undefined) {
                        document.getElementById('cart-icon-count').textContent = data.cart_count;
                    }
                    // If no items left, show empty message
                    if (document.querySelectorAll('.cart-item').length === 0) {
                        var list = document.querySelector('.dropdown-list-items');
                        if (list) {
                            list.innerHTML = '<li class="cart-item text-center text-muted">{{__('Cart is empty')}}</li>';
                        }
                        document.querySelector('.cart-total-price').textContent = '0';
                    }
                });
        }
    });
</script>