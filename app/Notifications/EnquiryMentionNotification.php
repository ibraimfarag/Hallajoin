<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Modules\Booking\Models\Enquiry;
use Modules\Booking\Models\EnquiryReply;

class EnquiryMentionNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $enquiry;

    protected $note;

    protected $mentionedBy;

    /**
     * Create a new notification instance.
     */
    public function __construct(Enquiry $enquiry, EnquiryReply $note, $mentionedBy)
    {
        $this->enquiry = $enquiry;
        $this->note = $note;
        $this->mentionedBy = $mentionedBy;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $url = route('report.admin.enquiry.reply', ['enquiry' => $this->enquiry->id]);

        return (new MailMessage)
            ->subject(__('You were mentioned in an enquiry note'))
            ->greeting(__('Hello!'))
            ->line(__(':name mentioned you in enquiry #:id', [
                'name' => $this->mentionedBy->getDisplayName(true),
                'id' => $this->enquiry->id,
            ]))
            ->line($this->note->content)
            ->action(__('View Enquiry'), $url)
            ->line(__('Thank you for using our application!'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'enquiry_id' => $this->enquiry->id,
            'note_id' => $this->note->id,
            'mentioned_by_id' => $this->mentionedBy->id,
            'mentioned_by_name' => $this->mentionedBy->getDisplayName(true),
            'content' => $this->note->content,
            'url' => route('report.admin.enquiry.reply', ['enquiry' => $this->enquiry->id]),
        ];
    }
}
