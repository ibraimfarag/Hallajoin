<div class="col-9">
    <div class="panel">
        <ul class="dropdown-list-items p-0">
            @if (count($rows) > 0)
                @foreach ($rows as $oneNotification)
                    @php
                        $active = $class = '';
                        $rawData = $oneNotification['data'];

                        // Handle both array and JSON string data
                        if (is_array($rawData)) {
                            $data = json_decode(json_encode($rawData)); // Convert nested arrays to objects
                        } else {
                            $data = json_decode($rawData);
                        }

                        $idNotification = $oneNotification->id;
                        $forAdmin = @$data->for_admin;

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
                        $title = @$usingData->message ?: @$usingData->title ?: @$data->message ?: @$data->title;
                        $name = @$usingData->name ?: @$data->title ?: '';
                        $avatar = @$usingData->avatar;
                        $link = @$usingData->link ?: @$data->link ?: '#';

                        if (empty($oneNotification->read_at)) {
                            $class = 'markAsRead';
                            $active = 'active';
                        }
                    @endphp
                    <li class="notification {{ $active }}">
                        <div class="media">
                            <div class="media-left">
                                <div class="media-object">
                                    @if ($avatar)
                                        <img class="image-responsive" src="{{ $avatar }}" alt="{{ $name }}">
                                    @elseif($name)
                                        <span class="avatar-text">{{ ucfirst($name[0]) }}</span>
                                    @else
                                        <span class="avatar-text">?</span>
                                    @endif
                                </div>
                            </div>
                            <div class="media-body">
                                <a class="{{ $class }} p-0" data-id="{{ $idNotification }}"
                                    href="{{ $link }}">{!! $title !!}</a>
                                <div class="notification-meta">
                                    <small class="timestamp">{{ format_interval($oneNotification->created_at) }}</small>
                                </div>
                            </div>
                        </div>
                    </li>
                @endforeach
            @else
                <li class="notification">{{ __("You don't have any notifications") }}</li>
            @endif
        </ul>

        <div class="d-flex justify-content-end">
            {{ $rows->links() }}
        </div>
    </div>
</div>
