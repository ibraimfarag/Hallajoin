<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'cart_id',
        'service_type',
        'service_id',
        'quantity',
        'price',
        'total_price',
        'booking_data',
    ];

    protected $casts = [
        'price' => 'float',
        'total_price' => 'float',
        'booking_data' => 'array',
    ];

    /**
     * Get the cart that owns the cart item.
     */
    public function cart(): BelongsTo
    {
        return $this->belongsTo(Cart::class);
    }

    /**
     * Get the service (polymorphic relationship).
     */
    public function service()
    {
        switch ($this->service_type) {
            case 'tour':
                return $this->belongsTo(\Modules\Tour\Models\Tour::class, 'service_id');
            case 'hotel':
                return $this->belongsTo(\Modules\Hotel\Models\Hotel::class, 'service_id');
            case 'car':
                return $this->belongsTo(\Modules\Car\Models\Car::class, 'service_id');
            case 'space':
                return $this->belongsTo(\Modules\Space\Models\Space::class, 'service_id');
            case 'boat':
                return $this->belongsTo(\Modules\Boat\Models\Boat::class, 'service_id');
            case 'event':
                return $this->belongsTo(\Modules\Event\Models\Event::class, 'service_id');
            case 'flight':
                return $this->belongsTo(\Modules\Flight\Models\Flight::class, 'service_id');
            default:
                \Illuminate\Support\Facades\Log::error("Unsupported service type encountered: {$this->service_type}");

                return null;
        }
    }

    /**
     * Get service instance dynamically.
     */
    public function getServiceAttribute()
    {
        switch ($this->service_type) {
            case 'tour':
                return \Modules\Tour\Models\Tour::find($this->service_id);
            case 'hotel':
                return \Modules\Hotel\Models\Hotel::find($this->service_id);
            case 'car':
                return \Modules\Car\Models\Car::find($this->service_id);
            case 'space':
                return \Modules\Space\Models\Space::find($this->service_id);
            case 'boat':
                return \Modules\Boat\Models\Boat::find($this->service_id);
            case 'event':
                return \Modules\Event\Models\Event::find($this->service_id);
            case 'flight':
                return \Modules\Flight\Models\Flight::find($this->service_id);
            default:
                return null;
        }
    }

    /**
     * Update total price based on quantity and price.
     */
    public function updateTotalPrice(): void
    {
        $this->total_price = round($this->quantity * $this->price, 2);
        $this->save();

        // Update cart total
        $this->cart->updateTotalAmount();
    }

    /**
     * Get service title.
     */
    public function getServiceTitleAttribute(): string
    {
        $service = $this->service;

        if ($service && method_exists($service, 'getAttribute') && $service->getAttribute('title')) {
            return $service->getAttribute('title');
        }

        return 'Unknown Service';
    }
}
