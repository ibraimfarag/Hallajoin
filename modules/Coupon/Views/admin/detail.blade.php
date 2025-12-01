@extends('admin.layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('module/report/css/create-order.css') }}">

<input type="hidden" id="csrf_token" value="{{ csrf_token() }}">
<style>
    .btn-mint-outline {
        background: transparent;
        border: 2px solid #059669;
        color: #059669;
        padding: 8px 24px;
        border-radius: 15px;
        font-weight: 600;
        font-size: 15px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: 0.3s;
        text-decoration: none;
    }

    .btn-mint-outline:hover,
    .btn-mint-outline:focus {
        background: #059669;
        color: #fff;
        text-decoration: none;
    }

    .switch-toggle {
        position: relative;
        width: 70px;
        height: 28px;
        display: inline-block;
    }

    .switch-toggle input[type="checkbox"] {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .switch-label {
        display: flex;
        align-items: center;
        background: #4caf50;
        border-radius: 14px;
        width: 70px;
        height: 28px;
        position: relative;
        cursor: pointer;
        transition: background 0.3s;
        top: -22px;
    }

    .switch-text-yes {
        color: #fff;
        font-size: 14px;
        font-weight: bold;
        margin-left: 10px;
        letter-spacing: 1px;
    }

    .switch-slider {
        position: absolute;
        right: 4px;
        top: 4px;
        width: 20px;
        height: 20px;
        background: #fff;
        border-radius: 50%;
        transition: right 0.3s;
    }

    .switch-toggle input[type="checkbox"]:not(:checked)+.switch-label {
        background: #ccc;
    }

    .switch-toggle input[type="checkbox"]:not(:checked)+.switch-label .switch-text-yes {
        color: #888;
    }

    .switch-toggle input[type="checkbox"]:not(:checked)+.switch-label .switch-slider {
        right: 46px;
    }

    .switch-toggle input[type="checkbox"]:checked+.switch-label .switch-slider {
        right: 4px;
    }

    .switch-toggle input[type="checkbox"]:checked+.switch-label {
        background: #4caf50;
    }

    /* Custom radio as square with checkmark */
    .custom-radio {
        position: relative;
        padding-left: 32px;
        cursor: pointer;
        font-size: 15px;
        user-select: none;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .custom-radio input[type="radio"] {
        position: absolute;
        opacity: 0;
        cursor: pointer;
        height: 0;
        width: 0;
    }

    .custom-radio .checkmark {
        position: absolute;
        left: 0;
        top: 50%;
        transform: translateY(-50%);
        height: 22px;
        width: 22px;
        background-color: #fff;
        border: 2px solid #059669;
        border-radius: 6px;
        box-sizing: border-box;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: border-color 0.2s;
    }

    .custom-radio input[type="radio"]:checked~.checkmark {
        border-color: #059669;
        background-color: #059669;
    }

    .custom-radio .checkmark:after {
        content: "";
        display: none;
        width: 10px;
        height: 10px;
        border-left: 3px solid #fff;
        border-bottom: 3px solid #fff;
        transform: rotate(-45deg);
        position: absolute;
        left: 5px;
        top: 2px;
    }

    .no-results {

        padding: 53px 16px !important;

    }

    .custom-radio input[type="radio"]:checked~.checkmark:after {
        display: block;
    }

    /* Activity search results */
    .search-results {
        max-height: 200px;
        overflow-y: auto;
    }

    .activity-item {
        display: flex;
        padding: 8px;
        /* border-bottom: 1px solid #eee; */
        cursor: pointer;
        align-items: center;
    }

    .activity-item:hover {
        background: #f9f9f9;
    }

    .activity-item.selected {
        background: #e6fffa;
        border-left: 4px solid #059669;
    }

    .activity-item-left img {
        border-radius: 6px;
    }

    .activity-item-body {
        font-weight: 600;
    }

    .dropdown-menu {
        color: #000;
    }

    body.dark-mode .dropdown-menu {
        color: #fff;
    }



     body.dark-mode .container-fluid {
    background: #0f1c2e;
}

</style>



<form class="needs-validation"
    action="{{ route('coupon.admin.store', ['id' => $row->id ? $row->id : '-1', 'lang' => request()->query('lang')]) }}"
    method="post">

    @csrf
    <div class="container-fluid">
        <div class="d-flex justify-content-between mb20">
            <div class="">
                <h1 class="title-bar">
                    {{ $row->id ? __('Edit: ') . $row->code : __('Add new Coupon') }}
                </h1>
            </div>
        </div>
        @include('admin.message')
        @if($row->id)
            @include('Language::admin.navigation')
        @endif
        <div class="lang-content-box">
            <div class="create-order-layout">
                <div class="activities-section">
                    @include('Coupon::admin.form')
                </div>
                <div class="cart-section">
                    <div class="panel">
                        <div class="panel-title"
                            style="display: flex;align-items: center;gap: 10px;border-bottom: 0;border-radius: 14px;">
                            <strong>{{ __('Active') }}</strong>
                            <div class="switch-toggle" style="margin-left: 0;">
                                <input type="checkbox" id="status_switch" name="status" value="publish" @if($row->status
                                == 'publish') checked @endif>
                                <label for="status_switch" class="switch-label">
                                    <span class="switch-text-yes">YES</span>
                                    <span class="switch-slider"></span>
                                </label>
                            </div>


                            <button class="btn btn-mint-outline" type="submit"
                                style="margin-left: 7%;display:inline-flex;align-items:center;gap:8px;font-weight:600;font-size:15px;">
                                <i class="fa fa-save" style="font-size:18px;"></i>
                                {{ __('save changes') }}
                            </button>

                        </div>


                        <!-- User selection now handled by customer search box below -->



                    </div>




                    <div class="section-card">
                        <div class="card-header">
                            <h3><i class="fa fa-user"></i> {{ __('Customer') }}</h3>
                        </div>
                        <div class="card-body">

                            <div class="form-group" style="margin-top: 20px;">
                                <label>{{ __('Apply Coupon To') }}<span
                                        class="text-danger">*</span></label>
                                <div style="display: flex; gap: 15px;">
                                    <label class="custom-radio">
                                        <input type="radio" name="apply_to" value="specific_user"
                                            @if(!empty($row->apply_to) && $row->apply_to == 'specific_user') checked
                                        @endif>
                                        <span class="checkmark"></span>
                                        {{ __('Per-User') }}
                                    </label>
                                    <label class="custom-radio">
                                        <input type="radio" name="apply_to" value="first_user"
                                            @if(!empty($row->apply_to) && $row->apply_to == 'first_user') checked
                                        @endif>
                                        <span class="checkmark"></span>
                                        {{ __('First User') }}
                                    </label>
                                    <label class="custom-radio">
                                        <input type="radio" name="apply_to" value="everyone" @if(!empty($row->apply_to)
                                        && $row->apply_to == 'everyone') checked @endif>
                                        <span class="checkmark"></span>
                                        {{ __('Public') }}
                                    </label>
                                </div>
                                <small
                                    class="text-muted">{{ __('First User = the coupon will be applied only on the user\'s first successful booking (no previous paid bookings).') }}</small>
                            </div>
                            <div class="form-group">
                                <div id="selectUserGroup" class="form-group"
                                    style="margin-top:12px; display:flex; align-items:center; gap:10px;">

                                    <input type="hidden" id="selectedUserId" name="only_for_user[]"
                                        value="{{ !empty($row->only_for_user) ? (is_array($row->only_for_user) ? ($row->only_for_user[0] ?? '') : $row->only_for_user) : '' }}">
                                </div>
                                <div id="selectedUserDisplay" style="display:flex;align-items:center;gap:10px;">
                                </div>
                            </div>
                            <div id="customerInfo" class="customer-info" style="display: none; margin-top:12px;">
                                @if(!empty($user))
                                    <div class="customer-avatar">
                                        @if(!empty($user['avatar']))
                                            <img src="{{ $user['avatar'] }}"
                                                style="width:48px;height:48px;border-radius:50%;object-fit:cover;">
                                        @else
                                            <div
                                                style="width:48px;height:48px;border-radius:50%;background:#059669;color:#fff;display:flex;align-items:center;justify-content:center;font-size:24px;font-weight:bold;">
                                                {{ strtoupper(mb_substr($user['name'],0,1)) }}
                                            </div>
                                        @endif
                                    </div>
                                    <div class="customer-details">
                                        <div class="customer-name">{{ $user['name'] }}</div>
                                        <div class="customer-phone">{{ $user['phone'] }}</div>
                                        <div class="customer-email">{{ $user['email'] }}</div>
                                    </div>
                                @else
                                    <div class="customer-avatar" id="customerAvatar"></div>
                                    <div class="customer-details">
                                        <div class="customer-name" id="customerName"></div>
                                        <div class="customer-phone" id="customerPhoneDisplay"></div>
                                        <div class="customer-email" id="customerEmail"></div>
                                    </div>
                                @endif
                                <button class="btn-remove-item" onclick="clearCustomer()" type="button">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </div>



                        </div>
                    </div>



                    <div class="activities-section">
                        <div class="section-card">
                            <div class="card-header">
                                <h3><i class="fa fa-search"></i> {{ __('Search Activities') }}</h3>
                            </div>
                            <div class="card-body">
                                <!-- Search Input -->
                                <div class="search-box">
                                    <input type="text" id="activitySearch" class="search-input"
                                        placeholder="{{ __('Search for activities...') }}">
                                    <i class="fa fa-search search-icon"></i>
                                </div>

                                <!-- Select field for the chosen activity (Select2) -->
                                <div class="form-group"
                                    style="margin-top:12px; display:flex; align-items:center; gap:8px;">
                                    <!-- Custom selected activity display -->
                                    <div id="selectedActivityDisplay"
                                        style="display:flex;align-items:center;gap:10px;padding:8px 0;"></div>
                                    <input type="hidden" id="selectedServiceInput" name="services[]"
                                        value="{{ $row->services && is_array($row->services) ? ($row->services[0] ?? '') : '' }}">
                                    <button id="clearSelectedService" type="button" class="btn btn-light"
                                        style="display:none;padding:6px 8px;border-radius:6px;border:1px solid #e5e7eb; margin-left:10px;">{{ __('Clear') }}</button>
                                </div>

                                <!-- Search Results -->
                                <div id="searchResults" class="search-results">
                                    <div class="no-results">
                                        <i class="fa fa-search"></i>
                                        <p>{{ __('Start typing to search for activities') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>




                        <!-- Activity details modal removed — selection now applied directly from search results -->



                    </div>

                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script>
        $(document).ready(function () {
            // عند تحميل الصفحة، إذا كان هناك مستخدم محفوظ، جلب بياناته وعرضها
            var initialUserId = $('#selectedUserId').val();
            if (initialUserId) {
                $.get("{{ route('user.admin.getForSelect2') }}", {
                    q: initialUserId
                }, function (res) {
                    if (res && res.results && res.results.length) {
                        var user = res.results[0];
                        selectedUser = {
                            id: user.id,
                            text: user.text,
                            avatar: user.avatar,
                            phone: user.phone,
                            email: user.email
                        };
                        renderSelectedUser(selectedUser);
                    }
                });
            }
            $('.has-datetimepicker').daterangepicker({
                singleDatePicker: true,
                timePicker: true,
                showCalendar: false,
                autoUpdateInput: false, //disable default date
                sameDate: true,
                autoApply: true,
                disabledPast: true,
                enableLoading: true,
                showEventTooltip: true,
                classNotAvailable: ['disabled', 'off'],
                disableHightLight: true,
                timePicker24Hour: true,
                locale: {
                    format: 'YYYY-MM-DD HH:mm:ss'
                }
            }).on('apply.daterangepicker', function (ev, picker) {
                $(this).val(picker.startDate.format('YYYY-MM-DD HH:mm:ss'));
            });


            // Custom user search and display logic (dropdown style)
            var selectedUser = null;

            function renderSelectedUser(user) {
                var $display = $('#selectedUserDisplay');
                if (!user) {
                    // امسح كل البيانات
                    $display.html('');
                    $('#selectedUserId').val('');
                    $('#clearSelectedUser').hide();
                    $('#customerInfo').hide();
                    // امسح محتوى معلومات العميل
                    $('#customerName').text('');
                    $('#customerPhoneDisplay').text('');
                    $('#customerEmail').text('');
                    $('#customerAvatar').html('');
                    return;
                }

                var img = user.avatar ? '<img src="' + user.avatar +
                    '" style="width:45px;height:45px;border-radius:50%;object-fit:cover;margin-right:8px;vertical-align:middle;">' :
                    '<div style="width:45px;height:45px;border-radius:50%;background:#059669;color:#fff;display:flex;align-items:center;justify-content:center;font-size:22px;font-weight:bold;">' +
                    (user.text ? user.text.trim().charAt(0).toUpperCase() : '?') + '</div>';

                $('#selectedUserId').val(user.id);
                $('#clearSelectedUser').show();

                // حدّث بيانات العميل
                $('#customerInfo').show();
                $('#customerName').text(user.text);
                $('#customerPhoneDisplay').text(user.phone ? user.phone : '');
                $('#customerEmail').text(user.email ? user.email : '');

                if (user.avatar) {
                    $('#customerAvatar').html('<img src="' + user.avatar +
                        '" style="width:48px;height:48px;border-radius:50%;object-fit:cover;">');
                } else {
                    var firstLetter = (user.text && user.text.length) ? user.text.trim().charAt(0)
                        .toUpperCase() : '?';
                    $('#customerAvatar').html(
                        '<div style="width:48px;height:48px;border-radius:50%;background:#059669;color:#fff;display:flex;align-items:center;justify-content:center;font-size:24px;font-weight:bold;">' +
                        firstLetter + '</div>');
                }
            }


            // User search input
            var userSearchWrapper = $(
                '<div id="userSearchWrapper" style="position:relative;display:inline-block;width:220px;"></div>'
            );
            var userSearchBox = $(
                '<input type="text" id="userSearch" class="form-control" placeholder="{{ __('Search by name or phone...') }}" style="width:220px;display:inline-block;">'
            );
            userSearchWrapper.append(userSearchBox);
            $('#selectedUserDisplay').before(userSearchWrapper);
            // Hide initially unless 'Specific User' is selected
            userSearchWrapper.hide();

            // Dropdown for user search results
            var userDropdown = $(
                '<div id="userSearchDropdown" class="dropdown-menu" style="display:none;position:absolute;z-index:1000;min-width:220px;max-height:180px;overflow-y:auto;"></div>'
            );
            userSearchBox.after(userDropdown);

            var userSearchTimer = null;
            userSearchBox.on('input', function () {
                var q = $(this).val();
                clearTimeout(userSearchTimer);
                userSearchTimer = setTimeout(function () {
                    if (!q || q.length < 1) {
                        userDropdown.hide();
                        userDropdown.html('');
                        return;
                    }
                    $.get('{{ route('user.admin.getForSelect2') }}', {
                        q: q
                    }, function (res) {
                        var html = '';
                        if (res && res.results && res.results.length) {
                            res.results.forEach(function (item) {
                                var img = item.avatar ? '<img src="' + item
                                    .avatar +
                                    '" style="width:32px;height:32px;border-radius:50%;object-fit:cover;margin-right:8px;vertical-align:middle;">' :
                                    '<div style="width:32px;height:32px;border-radius:50%;background:#059669;color:#fff;display:flex;align-items:center;justify-content:center;font-size:16px;font-weight:bold;">' +
                                    (item.text ? item.text.trim().charAt(0)
                                        .toUpperCase() : '?') + '</div>';
                                html +=
                                    '<div class="user-item" style="display:flex;align-items:center;gap:8px;cursor:pointer;padding:6px 12px;" data-id="' +
                                    item.id + '" data-name="' + item.text +
                                    '" data-avatar="' + (item.avatar || '') +
                                    '" data-phone="' + (item.phone || '') +
                                    '" data-email="' + (item.email || '') +
                                    '">' + img + '<span>' + item.text +
                                    '</span></div>';
                            });
                        } else {
                            html =
                                '<div class="no-results" style="padding:8px 12px;"><i class="fa fa-search"></i> <span>{{ __('No users found') }}</span></div>';
                        }
                        userDropdown.html(html);
                        userDropdown.show();
                    });
                }, 300);
            });

            // Click on user search result (dropdown)
            $(document).on('click', '#userSearchDropdown .user-item', function () {
                var id = $(this).data('id');
                var text = $(this).data('name');
                var avatar = $(this).data('avatar');
                var phone = $(this).data('phone');
                var email = $(this).data('email');
                selectedUser = {
                    id: id,
                    text: text,
                    avatar: avatar,
                    phone: phone,
                    email: email
                };
                renderSelectedUser(selectedUser);
                userSearchBox.val('');
                userDropdown.hide();
                userDropdown.html('');
            });

            // Hide dropdown if clicking outside
            $(document).on('mousedown', function (e) {
                if (!$(e.target).closest('#userSearch, #userSearchDropdown').length) {
                    userDropdown.hide();
                }
            });

            // Clear selected user
            $('#clearSelectedUser').on('click', function () {
                selectedUser = null;
                renderSelectedUser(null);
                userSearchBox.val('');
                userDropdown.hide();
                userDropdown.html('');
            });

            // Services select2 (editable in blade)
            function formatService(service) {
                if (!service.id) {
                    return service.text;
                }
                // service.image is provided by ajax results — fallback to option data-image for preselected options
                var imgUrl = service.image;
                if (!imgUrl && service.element) {
                    imgUrl = $(service.element).data('image');
                }
                var img = imgUrl ? '<img src="' + imgUrl +
                    '" style="width:45px;height:45px;border-radius:6px;object-fit:cover;margin-right:8px;vertical-align:middle;">' :
                    '';
                return $('<span>' + img + service.text + '</span>');
            }


            // Custom selected activity display logic
            function renderSelectedActivity(activity) {
                var $display = $('#selectedActivityDisplay');
                if (!activity) {
                    $display.html('');
                    $('#clearSelectedService').hide();
                    // امسح القيمة المخفية
                    $('#selectedServiceInput').val('');
                    return;
                }

                var img = activity.image ? '<img src="' + activity.image +
                    '" style="width:45px;height:45px;border-radius:6px;object-fit:cover;margin-right:8px;vertical-align:middle;">' :
                    '';
                var html = '<div style="display:flex;align-items:center;gap:10px;">' + img +
                    '<span style="font-weight:600;">' + activity.text + '</span></div>';
                $display.html(html);
                // حط القيمة في الـ input المخفي
                $('#selectedServiceInput').val(activity.id);
                $('#clearSelectedService').show();
            }


            // Store selected activity object
            var selectedActivity = null;

            // If there is a preselected service id from server, fetch details and render
            var initialServiceIds = @json($row -> services ?? []);
            if (initialServiceIds && initialServiceIds.length) {
                var initId = initialServiceIds[0];
                $.get('{{ route('coupon.admin.getServices') }}', {
                    q: initId
                }, function (res) {
                    if (res && res.results && res.results.length) {
                        var s = res.results[0];
                        selectedActivity = {
                            id: s.id,
                            text: s.text,
                            image: s.image
                        };
                        renderSelectedActivity(selectedActivity);
                        // set hidden input
                        $('#selectedServiceInput').val(s.id);
                    }
                });
            }

            // If there is a preselected service when page loads, show clear
            if ($('#servicesSelect').val()) {
                $('#clearSelectedService').show();
                var val = $('#servicesSelect').val();
                $('.activity-item').filter(function () {
                    return $(this).data('id').toString() === val.toString();
                }).addClass('selected');
            }
            // nothing


            // Clear selected activity via button
            $('#clearSelectedService').on('click', function () {
                selectedActivity = null;
                renderSelectedActivity(null);
                $('.activity-item').removeClass('selected');
                $(this).hide();
                // امسح القيمة المخفية
                $('#selectedServiceInput').val('');
                // امسح نتائج البحث وحقل البحث
                $('#searchResults').html(
                    '<div class="no-results"><i class="fa fa-search"></i><p>{{ __('Start typing to search for activities') }}</p></div>'
                );
                $('#activitySearch').val('');
            });

            // Remove select2 change sync logic (no longer needed)

            // Activity search in the panel (dynamic)
            var serviceSearchTimer = null;
            $('#activitySearch').on('input', function () {
                var q = $(this).val();
                clearTimeout(serviceSearchTimer);
                serviceSearchTimer = setTimeout(function () {
                    if (!q || q.length < 1) {
                        $('#searchResults').html(
                            '<div class="no-results"><i class="fa fa-search"></i><p>{{ __('Start typing to search for activities') }}</p></div>'
                        );
                        return;
                    }
                    $.get('{{ route('coupon.admin.getServices') }}', {
                        q: q
                    }, function (res) {
                        var html = '';
                        if (res && res.results && res.results.length) {
                            var selectedVal = $('#servicesSelect').val();
                            res.results.forEach(function (item) {
                                var img = item.image ? '<img src="' + item
                                    .image +
                                    '" style="width:64px;height:48px;object-fit:cover;border-radius:6px;margin-right:8px;">' :
                                    '';
                                var isSelected = (selectedVal && selectedVal
                                        .toString() === item.id.toString()) ?
                                    ' selected' : '';
                                html += '<div class="activity-item' +
                                    isSelected +
                                    '" style="display: flex;flex-direction: row;" data-id="' +
                                    item.id + '">';
                                html += '<div class="activity-item-left">' +
                                    img + '</div>';
                                html += '<div class="activity-item-body">' +
                                    item.text + '</div>';
                                html += '</div>';
                            });
                        } else {
                            html =
                                '<div class="no-results"><i class="fa fa-search"></i><p>{{ __('No activities found') }}</p></div>';
                        }
                        $('#searchResults').html(html);
                    });
                }, 300);
            });

            // Click on result to toggle selected activity
            $(document).on('click', '.activity-item', function () {
                var id = $(this).data('id');
                var text = $(this).find('.activity-item-body').text();
                var image = $(this).find('img').attr('src') || '';
                // If already selected, clear selection
                if (selectedActivity && selectedActivity.id.toString() === id.toString()) {
                    selectedActivity = null;
                    renderSelectedActivity(null);
                    $('.activity-item').removeClass('selected');
                    $('#clearSelectedService').hide();
                    return;
                }
                // otherwise set selected activity
                selectedActivity = {
                    id: id,
                    text: text,
                    image: image
                };
                renderSelectedActivity(selectedActivity);
                // Set hidden input so service id is sent on submit
                $('#selectedServiceInput').val(id);
                // highlight this result
                $('.activity-item').removeClass('selected');
                $(this).addClass('selected');
                // show clear button
                $('#clearSelectedService').show();
                // close any modal if accidentally open
                $('#activityDetailsModal').hide();

                // Hide search results and clear search field
                $('#searchResults').html('');
                $('#activitySearch').val('');
            });

            // Modal is no longer used — selection is applied directly from search results

            // Add to cart is not provided in the search selection UI here; select activity in the top field first

            function toggleCustomerInfoVisibility() {
                var selected = $('input[name="apply_to"]:checked').val();
                if (selected === 'specific_user') {
                    $('#selectUserGroup').show();
                    $('#userSelect').prop('disabled', false);
                    // Only show if user selected
                    if ($('#userSelect').val()) {
                        $('#customerInfo').show();
                    }
                } else {
                    $('#selectUserGroup').hide();
                    $('#userSelect').prop('disabled', true);
                    $('#customerInfo').hide();
                }
            }

            // Initial state
            $('#selectUserGroup').hide();
            toggleCustomerInfoVisibility();
            // Show user search if coupon is already set to specific_user
            var currentApplyTo = $('input[name="apply_to"]:checked').val();
            if (currentApplyTo === 'specific_user') {
                userSearchWrapper.show();
            } else {
                userSearchWrapper.hide();
            }

            $('input[name="apply_to"]').on('change', function () {
                var prev = $(this).data('prev') || 'specific_user';
                var selected = $('input[name="apply_to"]:checked').val();
                // If switching away from specific_user, clear user and hide search
                if (selected === 'specific_user') {
                    userSearchWrapper.show();
                } else {
                    userSearchWrapper.hide();
                    selectedUser = null;
                    renderSelectedUser(null);
                }
                $(this).data('prev', selected);
                toggleCustomerInfoVisibility();
            });

            window.clearCustomer = function () {
                selectedUser = null;
                renderSelectedUser(null);
                // امسح حقل البحث
                userSearchBox.val('');
                // اخفي وامسح الـ dropdown
                userDropdown.hide();
                userDropdown.html('');
                // امسح القيمة المخفية
                $('#selectedUserId').val('');
                // اخفي معلومات العميل
                $('#customerInfo').hide();
            }

        });




        // قبل إرسال الفورم، تأكد من حذف input المخفي إذا كانت قيمته فاضية
        $('form').on('submit', function (e) {
            var serviceValue = $('#selectedServiceInput').val();

            // إذا كانت القيمة فاضية أو null، أضف input مخفي بقيمة صريحة للحذف
            if (!serviceValue || serviceValue === '' || serviceValue === 'null') {
                // أزل الـ input القديم
                $('#selectedServiceInput').remove();

                // أضف input جديد بقيمة فاضية واضحة
                $(this).append('<input type="hidden" name="services" value="">');
            }

            // نفس الشيء للمستخدم
            var userValue = $('#selectedUserId').val();
            var applyTo = $('input[name="apply_to"]:checked').val();

            if (applyTo !== 'specific_user' || !userValue || userValue === '') {
                $('#selectedUserId').remove();
                if (applyTo === 'specific_user') {
                    $(this).append('<input type="hidden" name="only_for_user" value="">');
                }
            }
        });

    </script>
@endpush
