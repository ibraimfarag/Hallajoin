@extends('admin.layouts.app')

@section('content')
    <style>
        .notification-form-container {
            border-radius: 12px;
            padding: 30px;
        }

        body.dark-mode .notification-form-container {}

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

        body.dark-mode .form-control:focus {
            background: transparent;
            border-color: #25466ad6;
            color: var(--dark-text-primary);
            box-shadow: 0 0 0 0.2rem rgba(59, 130, 246, 0.25);
        }

        .form-control {
            border: 1px solid #ddd;
            border-radius: 6px;
            padding: 10px 12px;
            font-size: 14px;
            transition: border-color 0.3s;
            background: transparent;
            color: #333;
        }

        .form-control:focus {
            border-color: #25466ad6;
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
            min-height: 120px;
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

        .btn-submit {
            background: #224162;
            color: #fff;
            border: none;
            padding: 10px 30px;
            border-radius: 4px;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            transition: background 0.3s;
        }

        .btn-submit:hover {
            background: #1a2e4a;
        }

        .btn-secondary {
            background: #6c757d;
            color: #fff;
            border: none;
            padding: 10px 30px;
            border-radius: 4px;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            transition: background 0.3s;
        }

        .btn-secondary:hover {
            background: #5a6268;
        }

        .btn {
            color: #333;
            font-weight: 600;
            font-size: 14px;
            padding: 10px 30px;
            border: none;
            background: transparent;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .btn:hover {
            background: transparent;
        }

        body.dark-mode .btn {
            color: #e0e0e0;
        }

        body.dark-mode .btn:hover {
            background: transparent;
        }

        small.text-muted {
            color: #999;
            font-size: 12px;
            display: block;
            margin-top: 5px;
        }

        body.dark-mode small.text-muted {
            color: #aaa;
        }

        .section-title {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 20px;
            color: #333;
        }

        body.dark-mode .section-title {
            color: #e0e0e0;
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

        body.dark-mode .tags-input-container:focus-within {
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
            min-width: 120px;
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

        #user_ids_hidden {
            display: none;
        }

        .user-search-dropdown {
            position: absolute;
            background: #fff;
            border: 1px solid #059669;
            border-radius: 6px;
            max-height: 250px;
            overflow-y: auto;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            z-index: 1000;
            display: none;
            margin-top: 5px;
            min-width: 300px;
        }

        body.dark-mode .user-search-dropdown {
            background: #0f1c2e;
            border-color: #059669;
        }

        .user-search-item {
            padding: 12px 15px;
            cursor: pointer;
            border-bottom: 1px solid #f0f0f0;
            transition: background 0.2s;
        }

        body.dark-mode .user-search-item {
            border-bottom-color: #2a3f5f;
        }

        .user-search-item:hover {
            background: #f8f9fa;
        }

        body.dark-mode .user-search-item:hover {
            background: #1e3a5f;
        }

        .user-search-item:last-child {
            border-bottom: none;
        }

        .user-name {
            font-weight: 600;
            color: #333;
            margin-bottom: 4px;
        }

        body.dark-mode .user-name {
            color: #e0e0e0;
        }

        .user-details {
            font-size: 12px;
            color: #666;
        }

        body.dark-mode .user-details {
            color: #999;
        }

        .dropdown-loading,
        .dropdown-no-results {
            padding: 12px 15px;
            text-align: center;
            color: #666;
            font-size: 13px;
        }

        body.dark-mode .dropdown-loading,
        body.dark-mode .dropdown-no-results {
            color: #999;
        }

        .tags-wrapper {
            position: relative;
        }

        body.dark-mode .container-fluid {
            background: #0f1c2e;
        }


        .bg-form {
            background: #ffffff;
            flex: 1;
            padding: 30px 20px;
            border-radius: 20px;
            height: fit-content;
        }

        body.dark-mode .bg-form {

            background: #12243a;
        }
    </style>

    <div class="container-fluid" style="padding: 24px;">
        <div class="d-flex justify-content-between mb20">
            <h1 class="title-bar">{{ __('Send SMS') }}</h1>
        </div>

        @include('admin.message')

        <form action="{{ route('core.admin.send-sms.store') }}" method="POST">
            @csrf

            <div class="notification-form-container" style="display: flex; gap: 30px;">
                <!-- Left Side -->
                <div class="bg-form">
                    <div class="form-group">
                        <textarea id="message" name="message" class="form-control" placeholder="{{ __('Message (max 160 characters)') }}"
                            required maxlength="160"></textarea>
                        <div class="character-count">
                            <span id="charCount">0</span> / 160 {{ __('characters') }}
                        </div>
                        @error('message')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <!-- Right Side -->
                <div class="bg-form">
                    <div class="form-group">
                        <div class="tags-wrapper">
                            <div class="tags-input-container" id="tagsContainer">
                                <input type="text" id="tag_input" class="tag-input"
                                    placeholder="{{ __('Search by name, email, or phone...') }}" autocomplete="off">
                            </div>
                            <div class="user-search-dropdown" id="userDropdown"></div>
                        </div>
                        <input type="hidden" id="user_ids_hidden" name="user_ids">
                        <small class="text-muted">{{ __('Type to search users, click to add, or press Enter') }}</small>
                        @error('user_ids')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>
                            <input type="checkbox" id="send_to_all" name="send_to_all" value="1"
                                style="margin-right: 10px;">
                            <span>{{ __('Send To All') }}</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Bottom Buttons -->
            <div style="display: flex; gap: 10px; justify-content: flex-end; margin-top: 30px;">
                <button type="reset" class="btn ">
                    {{ __('CLEAR') }}
                </button>
                <button type="submit" class="btn btn-submit">
                    {{ __('Send') }}
                </button>
            </div>
        </form>
    </div>

    <script>
        console.log('SMS Send Script - Initialized');

        let tags = [];
        let selectedUsers = {};
        let searchTimeout = null;
        let currentSearchTerm = '';

        // Get DOM elements
        const messageInput = document.getElementById('message');
        const charCount = document.getElementById('charCount');
        const tagInput = document.getElementById('tag_input');
        const tagsContainer = document.getElementById('tagsContainer');
        const userDropdown = document.getElementById('userDropdown');
        const userIdsHidden = document.getElementById('user_ids_hidden');
        const sendToAllCheckbox = document.getElementById('send_to_all');
        const form = document.querySelector('form');

        // Update character count
        messageInput.addEventListener('input', function() {
            charCount.textContent = this.value.length;
        });

        // Add tag function
        function addTag(userId, userName) {
            userId = String(userId).trim();
            if (userId === '' || tags.includes(userId)) {
                return;
            }

            tags.push(userId);
            selectedUsers[userId] = userName;
            updateTagsDisplay();
            updateHiddenInput();
            tagInput.value = '';
            hideDropdown();
            console.log('✓ Tag added:', userId, userName);
        }

        // Remove tag function
        function removeTag(userId) {
            tags = tags.filter(tag => tag !== userId);
            delete selectedUsers[userId];
            updateTagsDisplay();
            updateHiddenInput();
            console.log('✓ Tag removed:', userId);
        }

        // Update tags display
        function updateTagsDisplay() {
            // Remove all existing tags
            const existingTags = tagsContainer.querySelectorAll('.tag-item');
            existingTags.forEach(tag => tag.remove());

            // Add tags before input
            tags.forEach(userId => {
                const userName = selectedUsers[userId] || userId;
                const tagElement = document.createElement('span');
                tagElement.className = 'tag-item';
                tagElement.innerHTML = userName +
                    '<span class="tag-remove" data-tag="' + userId + '">&times;</span>';
                tagsContainer.insertBefore(tagElement, tagInput);
            });
        }

        // Update hidden input with comma-separated values
        function updateHiddenInput() {
            userIdsHidden.value = tags.join(',');
            console.log('✓ Hidden input updated:', tags.join(','));
        }

        // Search users via AJAX
        function searchUsers(query) {
            if (query.length < 2) {
                hideDropdown();
                return;
            }

            currentSearchTerm = query;
            showDropdown('<div class="dropdown-loading">{{ __('Searching...') }}</div>');
            console.log('🔍 Searching for:', query);

            fetch("{{ route('core.admin.send-sms.getForSelect2') }}?q=" + encodeURIComponent(query))
                .then(response => response.json())
                .then(data => {
                    console.log('✓ Response received:', data);

                    if (currentSearchTerm !== query) return;

                    if (data.results && data.results.length > 0) {
                        let html = '';
                        data.results.forEach(user => {
                            html += '<div class="user-search-item" data-id="' + user.id +
                                '" data-name="' + user.text + '">';
                            html += '<div class="user-name">' + user.text + '</div>';
                            if (user.email || user.phone) {
                                html += '<div class="user-details">';
                                if (user.email) html += user.email;
                                if (user.email && user.phone) html += ' • ';
                                if (user.phone) html += user.phone;
                                html += '</div>';
                            }
                            html += '</div>';
                        });
                        showDropdown(html);
                        console.log('✓ Showing', data.results.length, 'results');
                    } else {
                        showDropdown('<div class="dropdown-no-results">{{ __('No users found') }}</div>');
                        console.log('ℹ No users found for:', query);
                    }
                })
                .catch(error => {
                    console.error('✗ Error:', error);
                    showDropdown('<div class="dropdown-no-results">{{ __('Error loading users') }}</div>');
                });
        }

        // Show dropdown
        function showDropdown(content) {
            userDropdown.innerHTML = content;
            userDropdown.style.display = 'block';
        }

        // Hide dropdown
        function hideDropdown() {
            userDropdown.style.display = 'none';
            userDropdown.innerHTML = '';
        }

        // Handle input typing
        tagInput.addEventListener('input', function() {
            const value = this.value;
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                searchUsers(value);
            }, 300);
        });

        // Handle Enter key
        tagInput.addEventListener('keydown', function(e) {
            const value = this.value;

            if (e.key === 'Enter') {
                e.preventDefault();
                const firstItem = userDropdown.querySelector('.user-search-item');
                if (firstItem) {
                    const userId = firstItem.getAttribute('data-id');
                    const userName = firstItem.getAttribute('data-name');
                    addTag(userId, userName);
                }
            }

            // Backspace on empty input
            if (e.key === 'Backspace' && value === '' && tags.length > 0) {
                removeTag(tags[tags.length - 1]);
            }

            // Escape to close dropdown
            if (e.key === 'Escape') {
                hideDropdown();
            }
        });

        // Handle clicking on user item
        document.addEventListener('click', function(e) {
            const userItem = e.target.closest('.user-search-item');
            if (userItem) {
                const userId = userItem.getAttribute('data-id');
                const userName = userItem.getAttribute('data-name');
                addTag(userId, userName);
            }

            const tagRemove = e.target.closest('.tag-remove');
            if (tagRemove) {
                const tag = tagRemove.getAttribute('data-tag');
                removeTag(tag);
            }
        });

        // Focus input when clicking container
        tagsContainer.addEventListener('click', function() {
            tagInput.focus();
        });

        // Hide dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.tags-wrapper')) {
                hideDropdown();
            }
        });

        // Handle "Send To All" checkbox
        sendToAllCheckbox.addEventListener('change', function() {
            if (this.checked) {
                tagsContainer.style.opacity = '0.5';
                tagsContainer.style.pointerEvents = 'none';
                tagInput.disabled = true;
                hideDropdown();
                console.log('✓ Send to all enabled');
            } else {
                tagsContainer.style.opacity = '1';
                tagsContainer.style.pointerEvents = 'auto';
                tagInput.disabled = false;
                console.log('✓ Send to all disabled');
            }
        });

        // Handle reset button
        const resetButton = document.querySelector('button[type="reset"]');
        if (resetButton) {
            resetButton.addEventListener('click', function() {
                tags = [];
                selectedUsers = {};
                updateTagsDisplay();
                updateHiddenInput();
                hideDropdown();
                charCount.textContent = '0';
                console.log('✓ Form reset');
            });
        }

        // Form submission
        form.addEventListener('submit', function(e) {
            const sendToAll = sendToAllCheckbox.checked;
            const userIds = userIdsHidden.value;

            if (!sendToAll && (!userIds || userIds.trim().length === 0)) {
                e.preventDefault();
                alert("{{ __('Please add user IDs or check \"Send To All\"') }}");
                console.log('✗ Validation failed: No user IDs selected');
                return false;
            }

            console.log('✓ Form submission valid');
            console.log('  Send to all:', sendToAll);
            console.log('  User IDs:', userIds);
        });

        console.log('✓ SMS Send Script - Ready');
    </script>
@endsection
