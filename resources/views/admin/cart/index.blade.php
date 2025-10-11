@extends('admin.layouts.app')

@section('content')
    <style>
        .cart-container {
            background: #0f1c2e;
            min-height: 100vh;
            padding: 24px;
            color: #e2e8f0;
        }

        .cart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 32px;
        }

        .cart-title {
            font-size: 24px;
            font-weight: 700;
            color: #ffffff;
        }

        .cart-table-container {
            background: #1a2942;
            border-radius: 12px;
            overflow: hidden;
        }

        .cart-table {
            width: 100%;
            border-collapse: collapse;
        }

        .cart-table thead {
            background: rgba(255, 255, 255, 0.03);
        }

        .cart-table th {
            padding: 16px;
            text-align: left;
            font-size: 18px;
            font-weight: 600;
            color: #8b92a7;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .cart-table td {
            padding: 16px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            color: #e2e8f0;
            font-size: 19px;
        }

        .cart-table tbody tr:hover {
            background: rgba(255, 255, 255, 0.03);
            transition: background 0.2s ease;
        }

        .user-cell {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 14px;
            color: white;
            flex-shrink: 0;
        }

        .user-info {
            display: flex;
            flex-direction: column;
        }

        .user-name {
            color: #ffffff;
            font-weight: 500;
            font-size: 17px;
        }

        .user-phone {
            color: #63b3ed;
            font-size: 19px;
            margin-top: 2px;
        }

        .cart-count-badge {
            background: rgba(99, 179, 237, 0.15);
            color: #63b3ed;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 13px;
            font-weight: 600;
            display: inline-block;
        }

        .view-cart-btn {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: #ffffff;
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 13px;
            cursor: pointer;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .view-cart-btn:hover {
            background: rgba(255, 255, 255, 0.15);
            text-decoration: none;
            color: #ffffff;
        }

        .eye-icon {
            color: #8b92a7;
            width: 20px;
            height: 20px;
            cursor: pointer;
            transition: color 0.2s ease;
        }

        .eye-icon:hover {
            color: #63b3ed;
        }

        td svg:hover {
            color: #63b3ed !important;
        }

        .date-cell {
            color: #a0aec0;
            font-size: 13px;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #8b92a7;
        }

        .empty-state i {
            font-size: 48px;
            margin-bottom: 16px;
            opacity: 0.3;
        }

        /* Modal Styles */
        .cart-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            z-index: 9999;
            align-items: center;
            justify-content: center;
        }

        .cart-modal.active {
            display: flex;
        }

        .cart-modal-content {
            background: #1a2942;
            border-radius: 30px;
            max-width: 670px;
            width: 90%;
            max-height: 80vh;
            overflow-y: auto;
            position: relative;
        }

        .cart-modal-header {
            padding: 20px 24px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .cart-modal-title {
            font-size: 18px;
            font-weight: 600;
            color: #ffffff;
        }

        .modal-close {
            background: none;
            border: none;
            color: #8b92a7;
            font-size: 24px;
            cursor: pointer;
            padding: 0;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            transition: all 0.2s ease;
        }

        .modal-close:hover {
            background: rgba(255, 255, 255, 0.1);
            color: #ffffff;
        }

        .cart-modal-body {
            padding: 24px;
        }

        .cart-item {
            display: flex;
            gap: 12px;
            padding: 12px;
            background: rgba(255, 255, 255, 0.03);
            border-radius: 8px;
            margin-bottom: 12px;
            align-items: flex-start;
        }

        .cart-item-image {
            width: 60px;
            height: 60px;
            border-radius: 6px;
            object-fit: cover;
            flex-shrink: 0;
        }

        .cart-item-details {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .cart-item-title {
            color: #ffffff;
            font-weight: 600;
            font-size: 13px;
            line-height: 1.4;
        }

        .cart-item-info {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .cart-item-info-row {
            color: #8b92a7;
            font-size: 11px;
            line-height: 1.5;
        }

        .cart-item-info-row strong {
            color: #a0aec0;
            font-weight: 500;
        }

        .cart-item-price {
            text-align: right;
            padding-left: 12px;
            flex-shrink: 0;
        }

        .cart-item-total {
            color: #63b3ed;
            font-weight: 600;
            font-size: 14px;
        }

        .cart-item-unit-price {
            color: #8b92a7;
            font-size: 11px;
            margin-top: 2px;
        }

        /* Loading Spinner */
        .loading-spinner {
            border: 3px solid rgba(99, 179, 237, 0.3);
            border-radius: 50%;
            border-top: 3px solid #63b3ed;
            width: 30px;
            height: 30px;
            animation: spin 1s linear infinite;
            margin: 0 auto;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>

    <div class="cart-container">
        <div class="cart-header">
            <h1 class="cart-title">Cart</h1>
        </div>

        <div class="cart-table-container">
            <table class="cart-table">
                <thead>
                    <tr>
                        <th>{{__('User')}}</th>
                        <th>{{__('Created On')}} <span style="margin-left: 4px; font-size: 12px;">▼</span></th>
                        <th style="text-align: center;">{{__('Cart Count')}}</th>
                        <th style="text-align: center;">{{__('Details')}}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($carts as $cart)
                        <tr>
                            <td>
                                <div class="user-cell">
                                    <div class="user-avatar">
                                        @if($cart->user && $cart->user->avatar)
                                            <img src="{{ $cart->user->avatar }}" alt="avatar"
                                                style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover;">
                                        @else
                                            {{ $cart->user ? strtoupper(substr($cart->user->first_name ?? $cart->user->name ?? 'G', 0, 1)) : 'G' }}
                                        @endif
                                    </div>
                                    <div class="user-info">
                                        @if($cart->user)
                                            <span class="user-name">{{ $cart->user->first_name ?? '' }}
                                                {{ $cart->user->last_name ?? '' }}</span>
                                            <span class="user-phone">{{ $cart->user->phone ?? $cart->user->email ?? 'N/A' }}</span>
                                        @else
                                            <span class="user-name">{{__('Guest')}}</span>
                                            <span class="user-phone">--</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="date-cell">
                                {{ $cart->created_at->format('d/M/Y H:i') }}
                            </td>
                            <td style="text-align: center;">
                                <span class="cart-count-badge">{{ $cart->items->count() }}</span>
                            </td>
                            <td style="text-align: center;">
                                <svg onclick="openCartModal({{ $cart->id }})"
                                    style="width: 30px; height: 30px; cursor: pointer; color: #8b92a7;"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">
                                <div class="empty-state">
                                    <i class="fas fa-shopping-cart"></i>
                                    <div>{{__('No carts found')}}</div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            @if($carts->hasPages())
                <div style="padding: 20px; border-top: 1px solid rgba(255, 255, 255, 0.1);">
                    {{ $carts->appends(request()->query())->links() }}
                </div>
            @endif
        </div>

        <!-- Cart Items Modal -->
        <div class="cart-modal" id="cartModal">
            <div class="cart-modal-content">
                <div class="cart-modal-header">
                    <h3 class="cart-modal-title">{{__('Cart Items')}}</h3>
                    <button class="modal-close" onclick="closeCartModal()">×</button>
                </div>
                <div class="cart-modal-body" id="cartModalBody">
                    <!-- Cart items will be loaded here -->
                </div>
            </div>
        </div>
    </div>

    <script>
        function openCartModal(cartId) {
            const modal = document.getElementById('cartModal');
            const modalBody = document.getElementById('cartModalBody');

            modal.classList.add('active');
            modalBody.innerHTML = '<div style="text-align: center; padding: 40px;"><div class="loading-spinner"></div><div style="margin-top: 10px; color: #63b3ed;">Loading...</div></div>';

            // Fetch cart items via AJAX
            fetch(`/admin/carts/${cartId}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success && data.items && data.items.length > 0) {
                        let itemsHtml = '';
                        data.items.forEach(item => {
                            itemsHtml += `
                                                                <div class="cart-item">
                                                                    <img src="${item.image || '/images/placeholder.jpg'}" alt="${item.title}" class="cart-item-image">
                                                                    <div class="cart-item-details">
                                                                        <div class="cart-item-title">${item.title}</div>
                                                                        ${item.category ? `<div class="cart-item-info-row" style=" font-size: 12px;">${item.category}</div>` : ''}
                                                                        <div class="cart-item-info">
                                                                            ${item.datetime ? `<div class="cart-item-info-row"><strong>${item.datetime}</strong></div>` : ''}
                                                                            ${item.quantity_text && item.quantity_text.length > 0 ? item.quantity_text.map(quantity => `<div class="cart-item-info-row"><strong>${quantity}</strong></div>`).join('') : ''}
                                                                        </div>
                                                                    </div>
                                                                    <div class="cart-item-price">
                                                                        <div class="cart-item-total">${item.total}</div>
                                                                        ${item.price !== item.total ? `<div class="cart-item-unit-price">${item.price} each</div>` : ''}
                                                                    </div>
                                                                </div>
                                                            `;
                        });
                        modalBody.innerHTML = itemsHtml;
                    } else {
                        modalBody.innerHTML = '<div class="empty-state"><i class="fas fa-inbox"></i><div>{{__("No items in cart")}}</div></div>';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    modalBody.innerHTML = '<div class="empty-state" style="color: #f56565;"><i class="fas fa-exclamation-triangle"></i><div>{{__("Error loading cart items")}}</div></div>';
                });
        }

        function closeCartModal() {
            document.getElementById('cartModal').classList.remove('active');
        }

        // Close modal when clicking outside
        document.getElementById('cartModal').addEventListener('click', function (e) {
            if (e.target === this) {
                closeCartModal();
            }
        });
    </script>
@endsection