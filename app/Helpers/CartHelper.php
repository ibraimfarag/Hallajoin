<?php

use App\Models\Cart;
use Illuminate\Support\Facades\Auth;

if (!function_exists('getUserCartData')) {
    /**
     * Get the current user's cart items and total price for the dropdown.
     *
     * @return array{items: array, total: float}
     */
    function getUserCartData(): array
    {
        $user = Auth::user();
        if (!$user) {
            return ['items' => [], 'total' => 0];
        }
        $cart = Cart::getActiveCartForUser($user->id);
        if (!$cart) {
            return ['items' => [], 'total' => 0];
        }
        $items = $cart->items()->get()->map(function ($item) {
            $service = $item->service;

            // تجهيز تفاصيل الحجز (تاريخ و أنواع الأفراد)
            $bookingInfo = [];
            $data = $item->booking_data ?? [];
            // التاريخ أو الموعد
            if (!empty($data['start_date'])) {
                $bookingInfo[] = __('Date') . ': ' . $data['start_date'];
            } elseif (!empty($data['date'])) {
                $bookingInfo[] = __('Date') . ': ' . $data['date'];
            } elseif (!empty($data['time'])) {
                $bookingInfo[] = __('Time') . ': ' . $data['time'];
            }
            // أنواع وعدد الأفراد
            if (!empty($data['person_types']) && is_array($data['person_types'])) {
                $types = [];
                foreach ($data['person_types'] as $type) {
                    if (!empty($type['name']) && !empty($type['number']) && $type['number'] > 0) {
                        $types[] = $type['name'] . ': ' . $type['number'];
                    }
                }
                if ($types) {
                    $bookingInfo[] = __('Persons') . ': ' . implode(', ', $types);
                }
            } elseif (!empty($data['guests'])) {
                $bookingInfo[] = __('Guests') . ': ' . $data['guests'];
            }

            return [
                'id' => $item->id,
                'name' => $service->title ?? $service->name ?? $item->service_type,
                'image_url' => $service->image_url ?? asset('images/default.png'),
                'quantity' => $item->quantity,
                'price' => $item->total_price,
                'booking_info' => $bookingInfo,
            ];
        })->toArray();
        $total = $cart->items()->sum('total_price');

        return [
            'items' => $items,
            'total' => $total,
        ];
    }
}
