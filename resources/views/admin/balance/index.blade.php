@extends('admin.layouts.app')

@section('content')
    <div class="container-fluid">

        @include('admin.message')

        <style>
            /* Light Mode & Dark Mode Variables */
            :root {
                /* Dark Mode Colors (Default) */
                --bal-bg-primary: #0f1c2e;
                --bal-bg-secondary: #1a2942;
                --bal-bg-hover: rgba(99, 179, 237, 0.05);
                --bal-text-primary: #ffffff;
                --bal-text-secondary: #8b92a7;
                --bal-border-color: rgba(255, 255, 255, 0.1);
                --bal-border-light: rgba(255, 255, 255, 0.05);
                --bal-shadow: rgba(0, 0, 0, 0.3);
            }

            /* Light Mode Override */
            [data-theme="light"] {
                --bal-bg-primary: #f7fafc;
                --bal-bg-secondary: #ffffff;
                --bal-bg-hover: #edf2f7;
                --bal-text-primary: #1a202c;
                --bal-text-secondary: #718096;
                --bal-border-color: #e2e8f0;
                --bal-border-light: #cbd5e0;
                --bal-shadow: rgba(0, 0, 0, 0.1);
            }
.breadcrumb{
    display: none;
}
            .balance-container {
           
                min-height: 100vh;
               

                transition: background-color 0.3s ease;

                margin-top: 80px;
            }

            .balance-card {
                background: var(--bal-bg-secondary);
                border-radius: 12px;
                box-shadow: 0 4px 6px var(--bal-shadow);
                border: 1px solid var(--bal-border-color);
                transition: all 0.3s ease;
            }

            .balance-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 24px;
                padding-bottom: 16px;
                border-bottom: 1px solid var(--bal-border-color);
            }

            .balance-title {
                font-size: 24px;
                font-weight: 600;
                color: var(--bal-text-primary);
                margin: 0;
            }

            .balance-table {
                width: 100%;
                border-collapse: separate;
                border-spacing: 0;
            }

            .balance-table thead th {
                background: rgba(99, 179, 237, 0.1);
                color: var(--bal-text-secondary);
                font-size: 19px;
                font-weight: 600;
                padding: 16px;
                text-align: left;
                border: none;
                white-space: nowrap;
            }

            .balance-table thead th:first-child {
                border-radius: 8px 0 0 0;
            }

            .balance-table thead th:last-child {
                border-radius: 0 8px 0 0;
                text-align: right;
            }

            .balance-table tbody tr {
                background: transparent;
                transition: background 0.2s;
            }

            .balance-table tbody tr:hover {
                background: var(--bal-bg-hover);
            }

            .balance-table tbody td {
                padding: 16px;
                color: var(--bal-text-primary);
                border-bottom: 1px solid var(--bal-border-light);
            }

            .balance-table tbody tr:last-child td {
                border-bottom: none;
            }

           .balance-table .user-info {
                display: flex;
                align-items: center;
                gap: 12px;
            }


           .balance-container .user-avatar-placeholder {
                width: 48px;
                height: 48px;
                border-radius: 50%;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 20px;
                font-weight: 600;
                color: #ffffff;
                border: 2px solid rgba(99, 179, 237, 0.3);
                flex-shrink: 0;
            }

           .balance-container .user-details {
                display: flex;
                flex-direction: column;
            }

            .balance-container .user-name {
                font-size: 17px;
                font-weight: 500;
                color: var(--bal-text-primary);
                margin-bottom: 4px;
            }

            .balance-container .user-phone {
                font-size: 19px;
                color: var(--bal-text-secondary);
            }

            .balance-amount {
                font-size: 19px;
                font-weight: 500;
                color: var(--bal-text-primary);
            }

            .balance-points {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                padding: 4px 12px;
                background: rgba(99, 179, 237, 0.15);
                border-radius: 20px;
                font-size: 13px;
                font-weight: 500;
                color: #63b3ed;
                margin-left: 20px;
            }

            .total-balance {
                font-size: 19px;
                font-weight: 600;
                text-align: right;
                padding-right: 30px;
            }

            .sort-icon {
                display: inline-block;
                margin-left: 4px;
                vertical-align: middle;
            }

            .empty-state {
                text-align: center;
                padding: 80px 20px;
                color: var(--bal-text-secondary);
            }

            .empty-state svg {
                margin: 0 auto 16px;
                opacity: 0.5;
            }

            .empty-state div {
                font-size: 16px;
                margin-top: 12px;
            }

            .balance-container a:hover {
                text-decoration: none !important;
            }

            /* Smooth transitions for theme changes */
            * {
                transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease;
            }
            
            body.dark-mode  .balance-container{

                    background: #0f1c2e;
    margin-top: 30px;
    padding: 0px 25px;
    margin-left: -17px;
    margin-right: -17px;
            }




        </style>

        <div class="balance-container">
             <h1 style="    font-size: 24px;
        font-weight: 700;    margin: 2px -1px 21px;
    padding-top: 26px;">{{ __('Balance') }}</h1>
            <div class="balance-card">

                @if($users->count() > 0)
                    <table class="balance-table">
                        <thead>
                            <tr>
                                <th>{{ __('User') }}</th>
                                <th>{{ __('Wallet') }}</th>
                                <th>{{ __('Points') }}</th>
                                <th >
                                    {{ __('Total Balance') }}
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                                <tr>
                                    <td>
                                        <div class="user-info">
                                            @if($user['has_avatar'])
                                                <img src="{{ $user['avatar'] }}" alt="{{ $user['name'] }}" class="user-avatar">
                                            @else
                                                <div class="user-avatar-placeholder">{{ $user['first_letter'] }}</div>
                                            @endif
                                            <div class="user-details">
                                                <a href="{{ route('user.admin.profile', ['id' => $user['id']]) }}"
                                                    class="user-name">
                                                    {{ $user['name'] }}
                                                </a>
                                                <a href="{{ route('user.admin.profile', ['id' => $user['id']]) }}" target="_blank"
                                                    class="user-profile-link">


                                                    @if($user['phone'])
                                                        <div class="user-phone"  style="color: #60a5fa;">{{ $user['phone'] }}</div>
                                                    @endif


                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="balance-amount">{{ $user['wallet'] }}{!! get_current_currency_svg() !!}</div>
                                    </td>
                                    <td>
                                        <span class="balance-points">{{ $user['points'] }}</span>
                                    </td>
                                    <td>
                                        <div class="total-balance">{{ $user['total_balance'] }}{!! get_current_currency_svg() !!}
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="empty-state">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width: 64px; height: 64px;" fill="currentColor"
                            viewBox="0 0 16 16">
                            <path
                                d="M1.5 1a.5.5 0 0 0-.5.5v3a.5.5 0 0 1-1 0v-3A1.5 1.5 0 0 1 1.5 0h3a.5.5 0 0 1 0 1h-3zM11 .5a.5.5 0 0 1 .5-.5h3A1.5 1.5 0 0 1 16 1.5v3a.5.5 0 0 1-1 0v-3a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 1-.5-.5zM.5 11a.5.5 0 0 1 .5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 1 0 1h-3A1.5 1.5 0 0 1 0 14.5v-3a.5.5 0 0 1 .5-.5zm15 0a.5.5 0 0 1 .5.5v3a1.5 1.5 0 0 1-1.5 1.5h-3a.5.5 0 0 1 0-1h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 1 .5-.5z" />
                            <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm8-9a3 3 0 1 1-6 0 3 3 0 0 1 6 0z" />
                        </svg>
                        <div>{{ __('No users found') }}</div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
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