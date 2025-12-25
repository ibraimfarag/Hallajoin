@extends('admin.layouts.app')

@section('content')
    <style>
        .messaging-container {
            display: flex;
            flex-direction: column;
            gap: 30px;
            padding: 24px;
        }

        .section-card {
            background: #ffffff;
            border-radius: 20px;
            padding: 30px;
            transition: box-shadow 0.3s;
        }

        body.dark-mode .section-card {
            background: #12243a;
        }

        .section-card:hover {
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.15);
        }

        .section-title {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 20px;
            color: #333;
            border-bottom: 2px solid #2241627c;
            ;
            padding-bottom: 12px;
            grid-column: 1 / -1;
        }

        body.dark-mode .section-title {
            color: #e0e0e0;
            border-bottom-color: #22416263;
        }

        .section-form {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            align-items: start;
        }

        .form-left {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .form-right {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            font-weight: 600;
            margin-bottom: 10px;
            display: block;
            color: #333;
            font-size: 14px;
        }

        body.dark-mode .form-group label {
            color: #e0e0e0;
        }

        .form-control {
            border: 1px solid #ddd;
            border-radius: 6px;
            padding: 10px 12px;
            font-size: 14px;
            transition: border-color 0.3s;
            background: transparent;
            color: #333;
            width: 100%;
            box-sizing: border-box;
        }

        .form-control:focus {
            border-color: #224162;
            box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.1);
            outline: none;
        }

        body.dark-mode .form-control {
            background: transparent;
            border-color: #25466ad6;
            color: #e0e0e0;
        }

        body.dark-mode .form-control:focus {
            border-color: #25466ad6;
        }

        textarea.form-control {
            min-height: 100px;
            resize: vertical;
            font-family: inherit;
        }

        .character-count {
            font-size: 12px;
            color: #666;
            margin-top: 5px;
        }

        body.dark-mode .character-count {
            color: #999;
        }

        .tags-input-container {
            border: 1px solid #b3b3b3;
            border-radius: 6px;
            padding: 8px;
            min-height: 42px;
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            align-items: center;
            background: transparent;
            cursor: text;
            transition: border-color 0.3s;
        }

        .tags-input-container:focus-within {
            border-color: #224162;
            box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.1);
        }

        body.dark-mode .tags-input-container {
            background: transparent;
            border-color: #224162;
        }

        .tag-item {
            background: #059669;
            color: #fff;
            padding: 5px 10px;
            border-radius: 4px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            font-weight: 500;
        }

        .tag-remove {
            cursor: pointer;
            font-weight: bold;
            font-size: 16px;
            line-height: 1;
            opacity: 0.8;
            transition: opacity 0.2s;
        }

        .tag-remove:hover {
            opacity: 1;
        }

        .tag-input {
            border: none;
            outline: none;
            background: transparent;
            flex: 1;
            min-width: 80px;
            padding: 4px;
            font-size: 14px;
            color: #333;
        }

        body.dark-mode .tag-input {
            color: #e0e0e0;
        }

        .tag-input::placeholder {
            color: #999;
        }

        body.dark-mode .tag-input::placeholder {
            color: #666;
        }

        .user-search-dropdown {
            position: absolute;
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            max-height: 280px;
            overflow-y: auto;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
            z-index: 1000;
            display: none;
            margin-top: 8px;
            min-width: 320px;
        }

        body.dark-mode .user-search-dropdown {
            background: #1a2d42;
            border-color: #2d4a6a;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.4);
        }

        .user-search-item {
            padding: 12px 16px;
            cursor: pointer;
            border-bottom: 1px solid #f3f4f6;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 14px;
        }

        body.dark-mode .user-search-item {
            border-bottom-color: #2d4a6a;
        }

        .user-search-item:last-child {
            border-bottom: none;
        }

        .user-search-item:hover {
            background: #f0f9ff;
        }

        body.dark-mode .user-search-item:hover {
            background: #243b53;
        }

        .user-avatar {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #e5e7eb;
            flex-shrink: 0;
        }

        body.dark-mode .user-avatar {
            border-color: #3d5a80;
        }

        .user-info {
            flex: 1;
            min-width: 0;
        }

        .user-name {
            font-weight: 600;
            color: #1f2937;
            font-size: 14px;
            margin-bottom: 4px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        body.dark-mode .user-name {
            color: #f3f4f6;
        }

        .user-phone {
            font-size: 12px;
            color: #6b7280;
            margin-bottom: 2px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .user-phone i {
            font-size: 11px;
            color: #9ca3af;
        }

        body.dark-mode .user-phone {
            color: #9ca3af;
        }

        body.dark-mode .user-phone i {
            color: #6b7280;
        }

        .user-email {
            font-size: 11px;
            color: #9ca3af;
            display: flex;
            align-items: center;
            gap: 6px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .user-email i {
            font-size: 10px;
            color: #d1d5db;
        }

        body.dark-mode .user-email {
            color: #6b7280;
        }

        body.dark-mode .user-email i {
            color: #4b5563;
        }

        .dropdown-loading,
        .dropdown-no-results {
            padding: 20px 16px;
            text-align: center;
            color: #6b7280;
            font-size: 13px;
        }

        .dropdown-loading i,
        .dropdown-no-results i {
            margin-right: 8px;
        }

        body.dark-mode .dropdown-loading,
        body.dark-mode .dropdown-no-results {
            color: #9ca3af;
        }

        .tags-wrapper {
            position: relative;
        }

        .form-actions {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #e0e0e0;
            grid-column: 1 / -1;
        }

        body.dark-mode .form-actions {
            border-top-color: #25466ad6;
        }

        .btn {
            font-weight: 600;
            font-size: 13px;
            padding: 10px 24px;
            border: 2px solid #e0e0e0;
            background: transparent;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            color: #6b7280;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn:hover {
            border-color: #dc3545;
            color: #dc3545;
            background: rgba(220, 53, 69, 0.05);
            transform: translateY(-1px);
        }

        .btn:active {
            transform: translateY(0);
        }

        body.dark-mode .btn {
            color: #9ca3af;
            border-color: #3d5a80;
        }

        body.dark-mode .btn:hover {
            border-color: #f87171;
            color: #f87171;
            background: rgba(248, 113, 113, 0.1);
        }

        .btn-submit {
            background: linear-gradient(135deg, #224162 0%, #1a3a5c 100%);
            color: #fff;
            font-weight: 600;
            font-size: 13px;
            padding: 10px 28px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 4px 12px rgba(34, 65, 98, 0.3);
        }

        .btn-submit:hover {
            background: linear-gradient(135deg, #1a3a5c 0%, #0f2840 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(34, 65, 98, 0.4);
        }

        .btn-submit:active {
            transform: translateY(0);
            box-shadow: 0 2px 8px rgba(34, 65, 98, 0.3);
        }

        body.dark-mode .btn-submit {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }

        body.dark-mode .btn-submit:hover {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            box-shadow: 0 6px 20px rgba(59, 130, 246, 0.4);
        }

        small.text-muted {
            color: #999;
            font-size: 11px;
            display: block;
            margin-top: 5px;
        }

        body.dark-mode small.text-muted {
            color: #aaa;
        }

        .text-danger {
            color: #dc3545;
            font-size: 11px;
            margin-top: 5px;
        }

        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        /* Modern Custom Checkbox */
        .checkbox-group input[type="checkbox"] {
            display: none;
        }

        .checkbox-group .custom-checkbox {
            position: relative;
            width: 22px;
            height: 22px;
            border: 2px solid #b3b3b3;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.25s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            background: transparent;
        }

        .checkbox-group .custom-checkbox:hover {
            border-color: #224162;
            transform: scale(1.05);
        }

        .checkbox-group input[type="checkbox"]:checked+.custom-checkbox {
            background: linear-gradient(135deg, #224162 0%, #1a3a5c 100%);
            border-color: #224162;
        }

        .checkbox-group .custom-checkbox::after {
            content: '';
            position: absolute;
            width: 6px;
            height: 11px;
            border: solid white;
            border-width: 0 2.5px 2.5px 0;
            transform: rotate(45deg) scale(0);
            transition: transform 0.2s ease;
            margin-top: -2px;
        }

        .checkbox-group input[type="checkbox"]:checked+.custom-checkbox::after {
            transform: rotate(45deg) scale(1);
        }

        /* Dark mode checkbox */
        body.dark-mode .checkbox-group .custom-checkbox {
            border-color: #3d5a80;
        }

        body.dark-mode .checkbox-group .custom-checkbox:hover {
            border-color: #4a90d9;
        }

        body.dark-mode .checkbox-group input[type="checkbox"]:checked+.custom-checkbox {
            background: linear-gradient(135deg, #4a90d9 0%, #3d7ac7 100%);
            border-color: #4a90d9;
        }

        .checkbox-group label {
            margin: 0;
            font-weight: 600;
            color: #333;
            cursor: pointer;
            user-select: none;
        }

        body.dark-mode .checkbox-group label {
            color: #e0e0e0;
        }

        .page-title-section {
            padding: 24px;
            background: #ffffff;
            border-radius: 12px;
            margin-bottom: 24px;
            margin-left: 24px;
            margin-right: 24px;
        }

        body.dark-mode .page-title-section {
            background: #12243a;
        }

        .title-bar {
            font-size: 24px;
            font-weight: 700;
            color: #333;
        }

        body.dark-mode .title-bar {
            color: #e0e0e0;
        }

        @media (max-width: 768px) {
            .messaging-container {
                flex-direction: column;
                gap: 20px;
                padding: 12px;
            }

            .section-card {
                padding: 20px;
            }

            .section-form {
                grid-template-columns: 1fr;
                gap: 20px;
            }
        }

        body.dark-mode .main-content .container-fluid {
            background: #0f1c2e;
        }

        input[type="checkbox"],
        input[type="radio"] {
            background: transparent;
        }
    </style>
    <div class="container-fluid" style="padding: 24px;">

        {{-- <div class="page-title-section"> --}}
        <h1 class="title-bar">{{ __('Marketing Messages') }}</h1>
        {{-- </div> --}}

        @include('admin.message')

        <div class="messaging-container">
            <!-- Notifications Section -->
            <div class="section-card">
                <h2 class="section-title">{{ __('Send Notifications') }}</h2>
                <form action="{{ route('core.admin.send-notification.store') }}" method="POST" class="section-form">
                    @csrf

                    <div class="form-left">
                        <div class="form-group">
                            <label for="notif_title">{{ __('Title') }}</label>
                            <input type="text" id="notif_title" name="title" class="form-control"
                                placeholder="{{ __('Notification title') }}" required>
                            @error('title')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="notif_message">{{ __('Message') }}</label>
                            <textarea id="notif_message" name="message" class="form-control" placeholder="{{ __('Notification message') }}"
                                required></textarea>
                            @error('message')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="notif_link">{{ __('Link (Optional)') }}</label>
                            <input type="url" id="notif_link" name="link" class="form-control"
                                placeholder="{{ __('https://example.com') }}">
                        </div>
                    </div>

                    <div class="form-right">
                        <div class="form-group">
                            <label>{{ __('Customer') }}</label>
                            <div class="tags-wrapper">
                                <div class="tags-input-container" id="notif_tagsContainer">
                                    <input type="text" id="notif_tag_input" class="tag-input"
                                        placeholder="{{ __('Search users...') }}" autocomplete="off">
                                </div>
                                <div class="user-search-dropdown" id="notif_userDropdown"></div>
                            </div>
                            <input type="hidden" id="notif_user_ids_hidden" name="user_ids">
                            <small class="text-muted">{{ __('Search and add users') }}</small>
                            @error('user_ids')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group">
                            <div class="checkbox-group">
                                <input type="checkbox" id="notif_send_to_all" name="send_to_all" value="1">
                                <span class="custom-checkbox"></span>
                                <label for="notif_send_to_all">{{ __('Send To All') }}</label>
                            </div>
                        </div>

                        <div style="margin-top: auto; display: flex; gap: 10px; justify-content: flex-end;">
                            <button type="reset" class="btn">{{ __('CLEAR') }}</button>
                            <button type="submit" class="btn btn-submit">{{ __('Send') }}</button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- SMS Section -->
            <div class="section-card">
                <h2 class="section-title">{{ __('Send SMS') }}</h2>
                <form action="{{ route('core.admin.send-sms.store') }}" method="POST" class="section-form">
                    @csrf

                    <div class="form-left">
                        <div class="form-group">
                            <label for="sms_message">{{ __('Message (max 160 characters)') }}</label>
                            <textarea id="sms_message" name="message" class="form-control" placeholder="{{ __('SMS message') }}" required
                                maxlength="160"></textarea>
                            <div class="character-count">
                                <span id="sms_charCount">0</span> / 160 {{ __('characters') }}
                            </div>
                            @error('message')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <div class="form-right">
                        <div class="form-group">
                            <label>{{ __('Customer') }}</label>
                            <div class="tags-wrapper">
                                <div class="tags-input-container" id="sms_tagsContainer">
                                    <input type="text" id="sms_tag_input" class="tag-input"
                                        placeholder="{{ __('Search users...') }}" autocomplete="off">
                                </div>
                                <div class="user-search-dropdown" id="sms_userDropdown"></div>
                            </div>
                            <input type="hidden" id="sms_user_ids_hidden" name="user_ids">
                            <small class="text-muted">{{ __('Search and add users') }}</small>
                            @error('user_ids')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group">
                            <div class="checkbox-group">
                                <input type="checkbox" id="sms_send_to_all" name="send_to_all" value="1">
                                <span class="custom-checkbox"></span>
                                <label for="sms_send_to_all">{{ __('Send To All') }}</label>
                            </div>
                        </div>

                        <div style="margin-top: auto; display: flex; gap: 10px; justify-content: flex-end;">
                            <button type="reset" class="btn">{{ __('CLEAR') }}</button>
                            <button type="submit" class="btn btn-submit">{{ __('Send') }}</button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Email Section -->
            <div class="section-card">
                <h2 class="section-title">{{ __('Send Email') }}</h2>
                <form action="{{ route('core.admin.send-email.store') }}" method="POST" class="section-form">
                    @csrf

                    <div class="form-left">
                        <div class="form-group">
                            <label for="email_subject">{{ __('Subject') }}</label>
                            <input type="text" id="email_subject" name="subject" class="form-control"
                                placeholder="{{ __('Email subject') }}" required maxlength="255">
                            @error('subject')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="email_message">{{ __('Message') }}</label>
                            <textarea id="email_message" name="message" class="form-control" placeholder="{{ __('Email message') }}" required></textarea>
                            @error('message')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <div class="form-right">
                        <div class="form-group">
                            <label>{{ __('Customer') }}</label>
                            <div class="tags-wrapper">
                                <div class="tags-input-container" id="email_tagsContainer">
                                    <input type="text" id="email_tag_input" class="tag-input"
                                        placeholder="{{ __('Search users...') }}" autocomplete="off">
                                </div>
                                <div class="user-search-dropdown" id="email_userDropdown"></div>
                            </div>
                            <input type="hidden" id="email_user_ids_hidden" name="user_ids">
                            <small class="text-muted">{{ __('Search and add users') }}</small>
                            @error('user_ids')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group">
                            <div class="checkbox-group">
                                <input type="checkbox" id="email_send_to_all" name="send_to_all" value="1">
                                <span class="custom-checkbox"></span>
                                <label for="email_send_to_all">{{ __('Send To All') }}</label>
                            </div>
                        </div>

                        <div style="margin-top: auto; display: flex; gap: 10px; justify-content: flex-end;">
                            <button type="reset" class="btn">{{ __('CLEAR') }}</button>
                            <button type="submit" class="btn btn-submit">{{ __('Send') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>



    </div>

    <script>
        // Custom checkbox click handler
        document.querySelectorAll('.custom-checkbox').forEach(function(checkbox) {
            checkbox.addEventListener('click', function() {
                const input = this.previousElementSibling;
                input.checked = !input.checked;
                input.dispatchEvent(new Event('change'));
            });
        });

        // Initialize SMS character counter
        const smsInput = document.getElementById('sms_message');
        const smsCharCount = document.getElementById('sms_charCount');
        smsInput.addEventListener('input', function() {
            smsCharCount.textContent = this.value.length;
        });

        // Helper function to initialize form
        function initializeForm(prefix) {
            let tags = [];
            let selectedUsers = {};
            let searchTimeout = null;
            let currentSearchTerm = '';

            const tagInput = document.getElementById(`${prefix}_tag_input`);
            const tagsContainer = document.getElementById(`${prefix}_tagsContainer`);
            const userDropdown = document.getElementById(`${prefix}_userDropdown`);
            const userIdsHidden = document.getElementById(`${prefix}_user_ids_hidden`);
            const sendToAllCheckbox = document.getElementById(`${prefix}_send_to_all`);
            const form = tagInput.closest('form');

            function addTag(userId, userName) {
                userId = String(userId).trim();
                if (userId === '' || tags.includes(userId)) return;

                tags.push(userId);
                selectedUsers[userId] = userName;
                updateTagsDisplay();
                updateHiddenInput();
                tagInput.value = '';
                hideDropdown();
            }

            function removeTag(userId) {
                tags = tags.filter(tag => tag !== userId);
                delete selectedUsers[userId];
                updateTagsDisplay();
                updateHiddenInput();
            }

            function updateTagsDisplay() {
                const existingTags = tagsContainer.querySelectorAll('.tag-item');
                existingTags.forEach(tag => tag.remove());

                tags.forEach(userId => {
                    const userName = selectedUsers[userId] || userId;
                    const tagElement = document.createElement('span');
                    tagElement.className = 'tag-item';
                    tagElement.innerHTML = userName + '<span class="tag-remove" data-tag="' + userId +
                        '">&times;</span>';
                    tagsContainer.insertBefore(tagElement, tagInput);
                });
            }

            function updateHiddenInput() {
                userIdsHidden.value = tags.join(',');
            }

            function searchUsers(query) {
                if (query.length < 2) {
                    hideDropdown();
                    return;
                }

                currentSearchTerm = query;
                showDropdown(
                    '<div class="dropdown-loading"><i class="fa fa-spinner fa-spin"></i>{{ __('Searching...') }}</div>'
                );

                const routeUrl = form.action.includes('notification') ?
                    '{{ route('core.admin.send-notification.getForSelect2') }}' :
                    form.action.includes('sms') ? '{{ route('core.admin.send-sms.getForSelect2') }}' :
                    '{{ route('core.admin.send-email.getForSelect2') }}';

                fetch(routeUrl + "?q=" + encodeURIComponent(query))
                    .then(response => response.json())
                    .then(data => {
                        if (currentSearchTerm !== query) return;

                        if (data.results && data.results.length > 0) {
                            let html = '';
                            data.results.forEach(user => {
                                const avatar = user.avatar || '{{ asset('images/avatar.png') }}';
                                const phone = user.phone || '';
                                const email = user.email || '';

                                html += '<div class="user-search-item" data-id="' + user.id + '" data-name="' +
                                    user.text + '">';
                                html += '<img src="' + avatar + '" alt="' + user.text +
                                    '" class="user-avatar" onerror="this.src=\'{{ asset('images/avatar.png') }}\'">';
                                html += '<div class="user-info">';
                                html += '<div class="user-name">' + user.text + '</div>';
                                if (phone) {
                                    html += '<div class="user-phone"><i class="fa fa-phone"></i>' + phone +
                                        '</div>';
                                }
                                if (email) {
                                    html += '<div class="user-email"><i class="fa fa-envelope"></i>' + email +
                                        '</div>';
                                }
                                html += '</div>';
                                html += '</div>';
                            });
                            showDropdown(html);
                        } else {
                            showDropdown(
                                '<div class="dropdown-no-results"><i class="fa fa-search"></i>{{ __('No users found') }}</div>'
                            );
                        }
                    })
                    .catch(() => {
                        showDropdown(
                            '<div class="dropdown-no-results"><i class="fa fa-exclamation-circle"></i>{{ __('Error loading users') }}</div>'
                        );
                    });
            }

            function showDropdown(content) {
                userDropdown.innerHTML = content;
                userDropdown.style.display = 'block';
            }

            function hideDropdown() {
                userDropdown.style.display = 'none';
            }

            tagInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    searchUsers(this.value);
                }, 300);
            });

            tagInput.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    const firstItem = userDropdown.querySelector('.user-search-item');
                    if (firstItem) {
                        addTag(firstItem.getAttribute('data-id'), firstItem.getAttribute('data-name'));
                    }
                }

                if (e.key === 'Backspace' && this.value === '' && tags.length > 0) {
                    removeTag(tags[tags.length - 1]);
                }

                if (e.key === 'Escape') {
                    hideDropdown();
                }
            });

            document.addEventListener('click', function(e) {
                const userItem = e.target.closest('.user-search-item');
                if (userItem && userItem.closest(`#${prefix}_userDropdown`)) {
                    addTag(userItem.getAttribute('data-id'), userItem.getAttribute('data-name'));
                }

                const tagRemove = e.target.closest('.tag-remove');
                if (tagRemove && tagRemove.closest(`#${prefix}_tagsContainer`)) {
                    removeTag(tagRemove.getAttribute('data-tag'));
                }
            });

            tagsContainer.addEventListener('click', function() {
                tagInput.focus();
            });

            document.addEventListener('click', function(e) {
                if (!e.target.closest(`.tags-wrapper`) || !e.target.closest(`#${prefix}_tagsContainer`)) {
                    hideDropdown();
                }
            });

            sendToAllCheckbox.addEventListener('change', function() {
                if (this.checked) {
                    tagsContainer.style.opacity = '0.5';
                    tagsContainer.style.pointerEvents = 'none';
                    tagInput.disabled = true;
                    hideDropdown();
                } else {
                    tagsContainer.style.opacity = '1';
                    tagsContainer.style.pointerEvents = 'auto';
                    tagInput.disabled = false;
                }
            });

            const resetButton = form.querySelector('button[type="reset"]');
            if (resetButton) {
                resetButton.addEventListener('click', function() {
                    tags = [];
                    selectedUsers = {};
                    updateTagsDisplay();
                    updateHiddenInput();
                    hideDropdown();
                });
            }

            form.addEventListener('submit', function(e) {
                const sendToAll = sendToAllCheckbox.checked;
                const userIds = userIdsHidden.value;

                if (!sendToAll && (!userIds || userIds.trim().length === 0)) {
                    e.preventDefault();
                    alert("{{ __('Please add user IDs or check \"Send To All\"') }}");
                    return false;
                }
            });
        }

        // Initialize all three forms
        initializeForm('notif');
        initializeForm('sms');
        initializeForm('email');
    </script>
@endsection
