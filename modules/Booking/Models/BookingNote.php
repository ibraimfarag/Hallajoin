<?php

namespace Modules\Booking\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class BookingNote extends Model
{
    protected $table = 'bravo_booking_notes';

    protected $fillable = [
        'booking_id',
        'user_id',
        'note',
        'attachments',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'attachments' => 'array',
    ];

    /**
     * Get the booking that owns the note.
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id');
    }

    /**
     * Get the user who created the note.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
