<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Benwilkins\FCM\FcmMessage;
use Carbon\Carbon;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class SendCertificateExpiry extends Notification
{
    use Queueable;

    protected $certificate;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($certificate)
    {
        $this->certificate = $certificate;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database', 'fcm'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }


    public function toFcm($notifiable): FcmMessage
    {
        $message = new FcmMessage();
        $notification = [
            'body' => 'your inventory '.$this->certificate->name.'Expired on '.Carbon::parse($this->certificate->expiry_date)->format('d M Y'),
            'title' => 'Inventory expiry',
            'image' => null,
        ];
        $data = [
            'click_action' => "FLUTTER_NOTIFICATION_CLICK",
            'sound' => 'default',
            'id' => 'bookings',
            'status' => 'done',
            'message' => $notification,
        ];
        $message->content($notification)->data($data)->priority(FcmMessage::PRIORITY_HIGH);

        return $message;
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
            'body' => 'your inventory '.$this->certificate->name.'Expired on '.Carbon::parse($this->certificate->expiry_date)->format('d M Y'),
            'title' => 'Inventory expiry',
        ];
    }

    /**
     * Route notifications for the FCM channel.
     *
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return string
    */
    
    public function routeNotificationForFcm($notification)
    {
        return $this->device_token;
    }
}
