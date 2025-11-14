@extends('admin.layouts.app')

@section('content')
<style>
    /* Light Mode & Dark Mode Variables */
    :root {
        /* Dark Mode Colors (Default) */
        --cart-bg-primary: #0f1c2e;
        --cart-bg-secondary: #1a2942;
        --cart-bg-hover: rgba(99, 179, 237, 0.1);
        --cart-text-primary: #ffffff;
        --cart-text-secondary: #e2e8f0;
        --cart-text-muted: #8b92a7;
        --cart-border-color: rgba(255, 255, 255, 0.1);
        --cart-border-light: rgba(255, 255, 255, 0.05);
    }

    /* Light Mode Override */
    [data-theme="light"] {
        --cart-bg-primary: #f7fafc;
        --cart-bg-secondary: #ffffff;
        --cart-bg-hover: #edf2f7;
        --cart-text-primary: #1a202c;
        --cart-text-secondary: #2d3748;
        --cart-text-muted: #718096;
        --cart-border-color: #e2e8f0;
        --cart-border-light: #cbd5e0;
    }

    .cart-container {
        background: var(--cart-bg-primary);
        min-height: 100vh;
        padding: 24px;
        color: var(--cart-text-secondary);
        transition: background-color 0.3s ease;
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
        color: var(--cart-text-primary);
    }

    .cart-table-container {
        background: var(--cart-bg-secondary);
        border-radius: 12px;
        overflow: hidden;
        border: 1px solid var(--cart-border-color);
        transition: all 0.3s ease;
    }

    .cart-table {
        width: 100%;
        border-collapse: collapse;
    }

    .cart-table thead {
        background: var(--cart-bg-hover);
    }

    .cart-table th {
        padding: 16px;
        text-align: left;
        font-size: 19px;
        font-weight: 600;
        color: var(--cart-text-muted);

        letter-spacing: 0.5px;
        border-bottom: 1px solid var(--cart-border-color);
    }

    .cart-table td {
        padding: 16px;
        border-bottom: 1px solid var(--cart-border-light);
        color: var(--cart-text-secondary);
        font-size: 19px;
    }

    .cart-table tbody tr:hover {
        background: var(--cart-bg-hover);
        transition: background 0.2s ease;
    }

    .cart-container .user-cell {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .cart-container .user-avatar {
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

    .cart-container .user-info {
        display: flex;
        flex-direction: column;
    }

    .cart-container .user-name {
        color: var(--cart-text-primary);
        font-weight: 500;
        font-size: 17px;
    }

    .cart-container .user-name:hover {
        color: #63b3ed !important;

    }

    .cart-container .user-phone {
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
        background: var(--cart-bg-hover);
        border: 1px solid var(--cart-border-color);
        color: var(--cart-text-primary);
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
        background: var(--cart-bg-hover);
        text-decoration: none;
        color: var(--cart-text-primary);
    }

    .eye-icon {
        color: var(--cart-text-muted);
        width: 20px;
        height: 20px;
        cursor: pointer;
        transition: color 0.2s ease;
    }

    .eye-icon:hover {
        color: #63b3ed;
    }

    td svg {
        color: var(--cart-text-muted);
    }

    td svg:hover {
        color: #63b3ed !important;
    }

    .date-cell {
        color: var(--cart-text-muted);
        font-size: 13px;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: var(--cart-text-muted);
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
        background: var(--cart-bg-secondary);
        border-radius: 30px;
        max-width: 670px;
        width: 90%;
        max-height: 80vh;
        overflow-y: auto;
        position: relative;
        border: 1px solid var(--cart-border-color);
    }

    .cart-modal-header {
        padding: 20px 24px;
        border-bottom: 1px solid var(--cart-border-color);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .cart-modal-title {
        font-size: 18px;
        font-weight: 600;
        color: var(--cart-text-primary);
    }

    .modal-close {
        background: none;
        border: none;
        color: var(--cart-text-muted);
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
        background: var(--cart-bg-hover);
        color: var(--cart-text-primary);
    }

    .cart-modal-body {
        padding: 24px;
    }

    .cart-item {
        display: flex;
        gap: 12px;
        padding: 12px;
        background: var(--cart-bg-hover);
        border-radius: 8px;
        margin-bottom: 12px;
        align-items: flex-start;
        border: 1px solid var(--cart-border-light);
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
        color: var(--cart-text-primary);
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
        color: var(--cart-text-muted);
        font-size: 11px;
        line-height: 1.5;
    }

    .cart-item-info-row strong {
        color: var(--cart-text-secondary);
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
        color: var(--cart-text-muted);
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

    .cart-container .user-cell a:hover {
        text-decoration: none !important;
    }

    /* Smooth transitions for theme changes */
    * {
        transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease;
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
                    <th>{{__('Last Updated')}}</th>
                    <th style="text-align: center;">{{__('Cart Count')}}</th>
                    <th style="text-align: center;">{{__('Details')}}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($carts as $cart)
                @if($cart->items->count() > 0)
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
                                <a href="{{ route('user.admin.profile', ['id' => $cart->user->id]) }}"
                                    class="user-name">
                                    {{ $cart->user->first_name ?? '' }} {{ $cart->user->last_name ?? '' }}
                                </a>
                                <a href="{{ route('user.admin.profile', ['id' => $cart->user->id]) }}" target="_blank"
                                    class="user-profile-link"
                                    style="color:#63b3ed; font-size:13px; word-break:break-all; margin-top:2px;">
                                    <span
                                        class="user-phone">{{ $cart->user->phone ?? $cart->user->email ?? 'N/A' }}</span>

                                </a>
                                @else
                                <span class="user-name">{{__('Guest')}}</span>
                                <span class="user-phone">--</span>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="date-cell">
                        {{ $cart->updated_at->format('d/M/Y H:i') }}
                    </td>
                    <td style="text-align: center;">
                        <span class="cart-count-badge">{{ $cart->items->count() }}</span>
                    </td>
                    <td style="text-align: center;">
                        <svg onclick="openCartModal('{{ $cart->id }}')"
                            style="width: 30px; height: 30px; cursor: pointer; color: #8b92a7;"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </td>
                </tr>
                @endif
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
                                                                                        ${item.price !== item.total ? `<div class="cart-item-unit-price d-none">${item.price} each</div>` : ''}
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
    document.getElementById('cartModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeCartModal();
        }
    });

    // Auto-detect theme from header switcher
    document.addEventListener('DOMContentLoaded', function() {
        const html = document.documentElement;

        // Check and apply theme
        function applyTheme() {
            // Get saved theme from localStorage (same as header switcher)
            const savedTheme = localStorage.getItem('admin-theme');

            // Check if dark-mode class exists on body or html
            const hasDarkClass = document.body.classList.contains('dark-mode') ||
                html.classList.contains('dark-mode') ||
                html.classList.contains('dark-mode-instant');

            // Apply light mode only if theme is explicitly light and no dark class
            if (savedTheme === 'light' && !hasDarkClass) {
                html.setAttribute('data-theme', 'light');
            } else if (savedTheme === 'dark' || hasDarkClass) {
                html.removeAttribute('data-theme');
            } else {
                // Default to light if no preference
                html.setAttribute('data-theme', 'light');
            }
        }

        // Apply theme immediately
        applyTheme();

        // Monitor for theme changes on body and html
        const observer = new MutationObserver(applyTheme);

        observer.observe(document.body, {
            attributes: true,
            attributeFilter: ['class']
        });

        observer.observe(html, {
            attributes: true,
            attributeFilter: ['class']
        });

        // Listen for localStorage changes (for theme switcher)
        window.addEventListener('storage', function(e) {
            if (e.key === 'admin-theme') {
                applyTheme();
            }
        });

        // Also check periodically (as backup)
        setInterval(applyTheme, 500);
    });
</script>
@endsection