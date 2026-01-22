<?php

return [
    'booking_route_prefix' => env('BOOKING_ROUTER_PREFIX', 'booking'),
    'statuses' => [
        'cancelled',
        'processing',
        'success',      // تم الدفع
        'completed',     // اكتملت
    ],
];
