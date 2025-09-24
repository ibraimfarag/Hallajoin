<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'session_id',
        'status',
        'total_amount',
    ];

    protected $casts = [
        'total_amount' => 'float',
    ];

    /**
     * Get the user that owns the cart.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\User::class);
    }

    /**
     * Get the cart items for the cart.
     */
    public function items(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    /**
     * Calculate and update the total amount.
     */
    public function updateTotalAmount(): void
    {
        $this->total_amount = $this->items()->sum('total_price');
        $this->save();
    }

    /**
     * Get the active cart for a user.
     */
    public static function getActiveCartForUser($userId)
    {
        return static::where('user_id', $userId)
            ->where('status', 'active')
            ->first();
    }

    /**
     * Get or create active cart for user.
     */
    public static function getOrCreateForUser($userId)
    {
        $cart = static::getActiveCartForUser($userId);

        if (! $cart) {
            $cart = static::create([
                'user_id' => $userId,
                'status' => 'active',
                'total_amount' => 0,
            ]);
        }

        return $cart;
    }
}
