<?php
if (!auth()->check()) {
    return;
}
[$notifications, $countUnread] = getNotify();
?>

<li class="dropdown-notifications dropdown p-0 mr-3">
    <a href="#" data-toggle="dropdown" class="is_login" style="display: flex; align-items: center; gap: 8px;">
        <span style="position: relative; display: inline-block;">
            <i class="fa fa-bell"></i>
            <span class="badge badge-danger orange-bg notification-icon">{{ $countUnread }}</span>
        </span>
        <span class="nav-text">{{ __('Notifications') }}</span>
        <i class="fa fa-angle-down"></i>
    </a>
    <ul class="dropdown-menu overflow-auto notify-items dropdown-container dropdown-menu-right dropdown-large">
        <div class="dropdown-toolbar">
            <div class="dropdown-toolbar-actions">
                <a href="#" class="markAllAsRead">{{ __('Mark all as read') }}</a>
            </div>
            <h3 class="dropdown-toolbar-title">{{ __('Notifications') }} (<span
                    class="notif-count">{{ $countUnread }}</span>)</h3>
        </div>
        <ul class="dropdown-list-items p-0">
            @if (count($notifications) > 0)
                @foreach ($notifications as $oneNotification)
                    @php
                        $active = $class = '';
                        $rawData = $oneNotification['data'];

                        // Handle data parsing - could be array or JSON string
                        if (is_array($rawData)) {
                            $data = json_decode(json_encode($rawData));
                        } else {
                            $data = json_decode($rawData);
                        }

                        $idNotification = $oneNotification->id;

                        // Check if data has nested notification object or direct properties
                        if (isset($data->notification) && is_object($data->notification)) {
                            $usingData = $data->notification;
                        } elseif (isset($data->notification) && is_array($data->notification)) {
                            $usingData = (object) $data->notification;
                        } else {
                            $usingData = $data;
                        }

                        $services = @$usingData->type;
                        $idServices = @$usingData->id;
                        // Try multiple fallbacks for title/message
                        $title = @$usingData->message ?: @$usingData->title ?: @$data->message ?: @$data->title;
                        $name = @$usingData->name ?: @$data->title ?: '';
                        $avatar = @$usingData->avatar;
                        // Try multiple fallbacks for link
                        $link = @$usingData->link ?: @$data->link ?: '#';

                        if (empty($oneNotification->read_at)) {
                            $class = 'markAsRead';
                            $active = 'active';
                        }
                    @endphp
                    <li class="notification {{ $active }}">
                        <a class="{{ $class }} p-0" data-id="{{ $idNotification }}" href="{{ $link }}">
                            <div class="media">
                                <div class="media-left">
                                    <div class="media-object">
                                        @if ($avatar)
                                            <img class="image-responsive" src="{{ $avatar }}"
                                                alt="{{ $name ?? 'User' }}">
                                        @else
                                            <span class="avatar-text">{{ $name ? ucfirst($name[0]) : 'N' }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="media-body">
                                    {!! $title !!}
                                    <div class="notification-meta">
                                        <small
                                            class="timestamp">{{ format_interval($oneNotification->created_at) }}</small>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </li>
                @endforeach
            @endif
        </ul>
        <div class="dropdown-footer text-center">
            <a href="{{ route('core.notification.loadNotify') }}">{{ __('View More') }}</a>
        </div>
    </ul>
</li>
