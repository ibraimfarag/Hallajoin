@extends('admin.layouts.app')

@section('content')
    <style>
        .favourite-container {
            background: #0f1c2e;
            min-height: 100vh;
            padding: 24px;
            color: #e2e8f0;
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
            color: #ffffff;
        }

        .favourite-table-container {
            background: #1a2942;
            border-radius: 12px;
            overflow: hidden;
        }

        .favourite-table {
            width: 100%;
            border-collapse: collapse;
        }

        .favourite-table thead {
            background: rgba(255, 255, 255, 0.03);
        }

        .favourite-table th {
            padding: 16px;
            text-align: left;
            font-size: 12px;
            font-weight: 600;
            color: #8b92a7;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .favourite-table td {
            padding: 16px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            color: #e2e8f0;
            font-size: 14px;
        }

        .favourite-table tbody tr:hover {
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
            font-size: 14px;
        }

        .user-phone {
            color: #63b3ed;
            font-size: 13px;
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
            color: #ffffff;
            font-weight: 500;
            font-size: 14px;
        }

        .service-type {
            color: #8b92a7;
            font-size: 12px;
            margin-top: 2px;
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
            background: #1a2942;
            border-radius: 30px;
            max-width: 700px;
            width: 90%;
            max-height: 80vh;
            overflow-y: auto;
            position: relative;
        }

        .favourite-modal-header {
            padding: 20px 24px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .favourite-modal-title {
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
            color: #ffffff;
            font-weight: 600;
            font-size: 18px;
        }

        .favourite-item-subtitle {
            color: #8b92a7;
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
            color: #a0aec0;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .favourite-item-info-row strong {
            color: #ffffff;
            font-weight: 600;
            min-width: 100px;
        }

        .favourite-item-price {
            color: #63b3ed;
            font-weight: 700;
            font-size: 20px;
            margin-top: 8px;
        }
    </style>

    <div class="favourite-container">
        <div class="favourite-header">
            <h1 class="favourite-title">{{__('Favourites')}}</h1>
        </div>

        <div class="favourite-table-container">
            <table class="favourite-table">
                <thead>
                    <tr>
                        <th>{{__('User')}}</th>
                        <th>{{__('Created On')}}
                            <svg xmlns="http://www.w3.org/2000/svg"
                                style="width: 12px; height: 12px; margin-left: 4px; display: inline-block; vertical-align: middle;"
                                fill="currentColor" viewBox="0 0 16 16">
                                <path d="M8 12l-4-4h8l-4 4z" />
                            </svg>
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
                                        <span class="user-name">{{ $user->first_name ?? '' }}
                                            {{ $user->last_name ?? '' }}</span>
                                        <span class="user-phone">{{ $user->phone ?? $user->email ?? 'N/A' }}</span>
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
                                <svg onclick="openFavouriteModal({{ $user->id }})"
                                    style="width: 20px; height: 20px; cursor: pointer; color: #8b92a7;"
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
                                                    <div class="favourite-item-subtitle">${item.subtitle}</div>
                                                    <div class="favourite-item-info" style="margin-top: 8px;">
                                                        ${item.location ? `<div class="favourite-item-info-row" style="font-size: 13px; color: #8b92a7; margin-bottom: 4px;"><svg xmlns="http://www.w3.org/2000/svg" style="width: 12px; height: 12px; margin-right: 6px; display: inline-block; vertical-align: middle;" fill="currentColor" viewBox="0 0 16 16"><path d="M8 0C5.2 0 3 2.2 3 5c0 3.5 5 11 5 11s5-7.5 5-11c0-2.8-2.2-5-5-5zm0 7.5c-1.4 0-2.5-1.1-2.5-2.5S6.6 2.5 8 2.5s2.5 1.1 2.5 2.5S9.4 7.5 8 7.5z"/></svg>${item.location}</div>` : ''}
                                                        <div style="font-size: 13px; color: #8b92a7;"><svg xmlns="http://www.w3.org/2000/svg" style="width: 12px; height: 12px; margin-right: 6px; display: inline-block; vertical-align: middle;" fill="currentColor" viewBox="0 0 16 16"><path d="M14 2h-1V1a1 1 0 0 0-2 0v1H5V1a1 1 0 0 0-2 0v1H2C.9 2 0 2.9 0 4v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zM2 14V7h12v7H2z"/></svg>${item.added_date}</div>
                                                    </div>
                                                </div>
                                                <div style="text-align: right;">
                                                    <div class="favourite-item-price" style="font-size: 16px; margin-bottom: 8px;">${item.price}</div>
                                                    <a href="${item.service_url}" target="_blank" style="color: #63b3ed; font-size: 13px; text-decoration: none; display: inline-flex; align-items: center; gap: 4px;">
                                                        <svg xmlns="http://www.w3.org/2000/svg" style="width: 12px; height: 12px;" fill="currentColor" viewBox="0 0 16 16"><path d="M6.354 5.5H4a3 3 0 0 0 0 6h3a3 3 0 0 0 2.83-4H9c-.086 0-.17.01-.25.031A2 2 0 0 1 7 10.5H4a2 2 0 1 1 0-4h1.535c.218-.376.495-.714.82-1z"/><path d="M9 5.5a3 3 0 0 0-2.83 4h1.098A2 2 0 0 1 9 6.5h3a2 2 0 1 1 0 4h-1.535a4.02 4.02 0 0 1-.82 1H12a3 3 0 1 0 0-6H9z"/></svg> View
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
    </script>
@endsection