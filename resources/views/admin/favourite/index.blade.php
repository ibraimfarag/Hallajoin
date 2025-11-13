@extends('admin.layouts.app')

@section('content')
    <style>
        /* Light Mode & Dark Mode Variables */
        :root {
            /* Dark Mode Colors (Default) */
            --fav-bg-primary: #0f1c2e;
            --fav-bg-secondary: #1a2942;
            --fav-bg-hover: rgba(99, 179, 237, 0.1);
            --fav-text-primary: #ffffff;
            --fav-text-secondary: #e2e8f0;
            --fav-text-muted: #8b92a7;
            --fav-border-color: rgba(255, 255, 255, 0.1);
            --fav-border-light: rgba(255, 255, 255, 0.05);
        }

        /* Light Mode Override */
        [data-theme="light"] {
            --fav-bg-primary: #f7fafc;
            --fav-bg-secondary: #ffffff;
            --fav-bg-hover: #edf2f7;
            --fav-text-primary: #1a202c;
            --fav-text-secondary: #2d3748;
            --fav-text-muted: #718096;
            --fav-border-color: #e2e8f0;
            --fav-border-light: #cbd5e0;
        }

        .favourite-container {
            background: var(--fav-bg-primary);
            min-height: 100vh;
            padding: 24px;
            color: var(--fav-text-secondary);
            transition: background-color 0.3s ease;
        }

        .favourite-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 32px;
        }

        .favourite-title {
            font-size: 24px;
            font-weight: 700;
            color: var(--fav-text-primary);
        }

        .favourite-table-container {
            background: var(--fav-bg-secondary);
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid var(--fav-border-color);
            transition: all 0.3s ease;
        }

        .favourite-table {
            width: 100%;
            border-collapse: collapse;
        }

        .favourite-table thead {
            background: var(--fav-bg-hover);
        }

        .favourite-table th {
            padding: 16px;
            text-align: left;
            font-size: 19px;
            font-weight: 600;
            color: var(--fav-text-muted);
            letter-spacing: 0.5px;
            border-bottom: 1px solid var(--fav-border-color);
        }

        .favourite-table td {
            padding: 16px;
            border-bottom: 1px solid var(--fav-border-light);
            color: var(--fav-text-secondary);
            font-size: 14px;
        }

        .favourite-table tbody tr:hover {
            background: var(--fav-bg-hover);
            transition: background 0.2s ease;
        }

        .favourite-container .user-cell {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .favourite-container .user-avatar {
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

        .favourite-container .user-info {
            display: flex;
            flex-direction: column;
        }

        .favourite-container .user-name {
            color: var(--fav-text-primary);
            font-weight: 500;
            font-size: 17px;
        }

        .favourite-container .user-phone {
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

        .service-cell {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .service-image {
            width: 50px;
            height: 50px;
            border-radius: 6px;
            object-fit: cover;
            flex-shrink: 0;
        }

        .service-info {
            display: flex;
            flex-direction: column;
        }

        .service-title {
            color: var(--fav-text-primary);
            font-weight: 500;
            font-size: 14px;
        }

        .service-type {
            color: var(--fav-text-muted);
            font-size: 12px;
            margin-top: 2px;
        }

        .date-cell {
            color: var(--fav-text-muted);
            font-size: 17px !important;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: var(--fav-text-muted);
        }

        .empty-state i {
            font-size: 48px;
            margin-bottom: 16px;
            opacity: 0.3;
        }

        .eye-icon {
            color: var(--fav-text-muted);
            width: 20px;
            height: 20px;
            cursor: pointer;
            transition: color 0.2s ease;
        }

        .eye-icon:hover {
            color: #63b3ed;
        }

        td svg {
            color: var(--fav-text-muted);
        }

        td svg:hover {
            color: #63b3ed !important;
        }

        /* Modal Styles */
        .favourite-modal {
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

        .favourite-modal.active {
            display: flex;
        }

        .favourite-modal-content {
            background: var(--fav-bg-secondary);
            border-radius: 30px;
            max-width: 670px;
            width: 90%;
            max-height: 80vh;
            overflow-y: auto;
            position: relative;
            border: 1px solid var(--fav-border-color);
        }

        .favourite-modal-header {
            padding: 20px 24px;
            border-bottom: 1px solid var(--fav-border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .favourite-modal-title {
            font-size: 18px;
            font-weight: 600;
            color: var(--fav-text-primary);
        }

        .modal-close {
            background: none;
            border: none;
            color: var(--fav-text-muted);
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
            background: var(--fav-bg-hover);
            color: var(--fav-text-primary);
        }

        .favourite-modal-body {
            padding: 24px;
        }

        .favourite-item {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .favourite-item-image {
            width: 100%;
            height: 200px;
            border-radius: 8px;
            object-fit: cover;
        }

        .favourite-item-title {
            color: var(--fav-text-primary);
            font-weight: 600;
            font-size: 18px;
        }

        .favourite-item-subtitle {
            color: var(--fav-text-muted);
            font-size: 14px;
            margin-top: 4px;
        }

        .favourite-item-info {
            display: flex;
            flex-direction: column;
            gap: 8px;
            margin-top: 8px;
        }

        .favourite-item-info-row {
            color: var(--fav-text-secondary);
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .favourite-item-info-row strong {
            color: var(--fav-text-primary);
            font-weight: 600;
            min-width: 100px;
        }

        .favourite-item-price {
            color: #63b3ed;
            font-weight: 700;
            font-size: 20px;
            margin-top: 8px;
        }

        .favourite-container .user-cell a:hover {
            text-decoration: none !important;
        }

        /* Smooth transitions for theme changes */
        * {
            transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease;
        }
    </style>

    <div class="favourite-container">
        <div class="favourite-header">
            <h1 class="favourite-title">{{__('Bookmark')}}</h1>
        </div>

        <div class="favourite-table-container">
            <table class="favourite-table">
                <thead>
                    <tr>
                        <th>{{__('User')}}</th>
                        <th>{{__('Created On')}}
                          
                        </th>
                        <th style="text-align: center;">{{__('Activity Count')}}</th>
                        <th style="text-align: center;">{{__('Details')}}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td>
                                <div class="user-cell">
                                    <div class="user-avatar">
                                        @if($user->avatar)
                                            <img src="{{ $user->avatar }}" alt="avatar"
                                                style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover;">
                                        @else
                                            {{ strtoupper(substr($user->first_name ?? $user->name ?? 'G', 0, 1)) }}
                                        @endif
                                    </div>
                                    <div class="user-info">
                                        <a href="{{ route('user.admin.profile', ['id' => $user->id]) }}" class="user-name">
                                            {{ $user->first_name ?? '' }} {{ $user->last_name ?? '' }}
                                        </a>
                                        <a href="{{ route('user.admin.profile', ['id' => $user->id]) }}" target="_blank"
                                            class="user-profile-link">
                                            <span class="user-phone">{{ $user->phone ?? $user->email ?? 'N/A' }}</span>
                                        </a>
                                    </div>
                                </div>
                            </td>
                            <td class="date-cell">
                                {{ \Carbon\Carbon::parse($user->last_added)->format('d/M/Y H:i') }}
                            </td>
                            <td style="text-align: center;">
                                <span class="cart-count-badge">{{ $user->favourites_count }}</span>
                            </td>
                            <td style="text-align: center;">
                                <svg onclick="openFavouriteModal('{{ $user->id }}')"
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
                                    <i class="fas fa-heart"></i>
                                    <div>{{__('No favourites found')}}</div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            @if($users->hasPages())
                <div style="padding: 20px; border-top: 1px solid rgba(255, 255, 255, 0.1);">
                    {{ $users->appends(request()->query())->links() }}
                </div>
            @endif
        </div>

        <!-- User Favourites Modal -->
        <div class="favourite-modal" id="favouriteModal">
            <div class="favourite-modal-content">
                <div class="favourite-modal-header">
                    <h3 class="favourite-modal-title">{{__('Bookmarks')}}</h3>
                    <button class="modal-close" onclick="closeFavouriteModal()">×</button>
                </div>
                <div class="favourite-modal-body" id="favouriteModalBody">
                    <!-- User favourites will be loaded here -->
                </div>
            </div>
        </div>
    </div>

    <script>
        function openFavouriteModal(userId) {
            const modal = document.getElementById('favouriteModal');
            const modalBody = document.getElementById('favouriteModalBody');

            modal.classList.add('active');
            modalBody.innerHTML = '<div style="text-align: center; padding: 40px;"><svg xmlns="http://www.w3.org/2000/svg" style="width: 24px; height: 24px; animation: spin 1s linear infinite;" fill="none" viewBox="0 0 24 24"><circle style="opacity: 0.25;" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path style="opacity: 0.75;" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg><style>@keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }</style></div>';

            // Fetch user's favourites via AJAX
            fetch(`/admin/favourites/${userId}`, {
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
                                                        <div class="favourite-item" style="margin-bottom: 16px; padding-bottom: 16px; border-bottom: 1px solid rgba(255, 255, 255, 0.1);">
                                                            <div style="display: flex; gap: 16px; align-items: flex-start;">
                                                                <img src="${item.image}" alt="${item.title}" style="width: 100px; height: 100px; border-radius: 8px; object-fit: cover; flex-shrink: 0;">
                                                                <div style="flex: 1;">
                                                                    <div class="favourite-item-title">${item.title}</div>
                                                                    ${item.category ? `<div style="color: #8b92a7; font-size: 12px; margin-top: 4px;">${item.category}</div>` : ''}

                                                                </div>
                                                                <div style="text-align: right;">
                                                                    <div class="favourite-item-price" style="font-size: 16px; margin-bottom: 8px;">${item.price}</div>
                                                                    <a href="${item.service_url}" target="_blank" style="color: #63b3ed; font-size: 13px; text-decoration: none; display: inline-flex; align-items: center; gap: 4px;">
                                                                View
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    `;
                        });
                        modalBody.innerHTML = itemsHtml;
                    } else {
                        modalBody.innerHTML = '<div class="empty-state"><svg xmlns="http://www.w3.org/2000/svg" style="width: 48px; height: 48px; margin-bottom: 16px;" fill="currentColor" viewBox="0 0 16 16"><path d="M4 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H4zm0 1h8a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1z"/></svg><div>{{__("No bookmarks found")}}</div></div>';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    modalBody.innerHTML = '<div class="empty-state" style="color: #f56565;"><svg xmlns="http://www.w3.org/2000/svg" style="width: 48px; height: 48px; margin-bottom: 16px;" fill="currentColor" viewBox="0 0 16 16"><path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/></svg><div>{{__("Error loading bookmarks")}}</div></div>';
                });
        }

        function closeFavouriteModal() {
            document.getElementById('favouriteModal').classList.remove('active');
        }

        // Close modal when clicking outside
        document.getElementById('favouriteModal').addEventListener('click', function (e) {
            if (e.target === this) {
                closeFavouriteModal();
            }
        });

        // Auto-detect theme from header switcher
        document.addEventListener('DOMContentLoaded', function () {
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
            window.addEventListener('storage', function (e) {
                if (e.key === 'admin-theme') {
                    applyTheme();
                }
            });

            // Also check periodically (as backup)
            setInterval(applyTheme, 500);
        });
    </script>
@endsection