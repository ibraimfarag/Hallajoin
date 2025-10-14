<?php

namespace Modules\Booking\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Modules\Booking\Models\Booking;
use Modules\Booking\Models\BookingNote;

class BookingNoteMentionNotification extends Notification
{
    use Queueable;

    protected $booking;

    protected $note;

    protected $mentionedBy;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Booking $booking, BookingNote $note, $mentionedBy)
    {
        $this->booking = $booking;
        $this->note = $note;
        $this->mentionedBy = $mentionedBy;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'type' => 'booking_note_mention',
            'booking_id' => $this->booking->id,
            'booking_code' => $this->booking->code,
            'note_id' => $this->note->id,
            'note_content' => $this->note->note,
            'mentioned_by_id' => $this->mentionedBy->id,
            'mentioned_by_name' => $this->mentionedBy->getDisplayName(),
            'mentioned_by_avatar' => $this->mentionedBy->getAvatarUrl(),
            'message' => __(':user mentioned you in a note on order #:code', [
                'user' => $this->mentionedBy->getDisplayName(),
                'code' => $this->booking->code,
            ]),
            'link' => route('report.admin.booking', ['code' => $this->booking->code]),
        ];
    }
}
