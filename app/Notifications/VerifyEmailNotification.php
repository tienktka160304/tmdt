<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Carbon;

class VerifyEmailNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        // Tạo URL xác nhận đúng format Laravel
        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify', // Route có sẵn của Laravel
            Carbon::now()->addMinutes(60), // Hết hạn sau 60 phút
            ['id' => $notifiable->getKey(), 'hash' => sha1($notifiable->getEmailForVerification())]
        );


        return (new MailMessage)
            ->subject('Xác nhận địa chỉ email của bạn')
            ->greeting('Xin chào!')
            ->line('Vui lòng nhấn vào nút bên dưới để xác nhận địa chỉ email của bạn:')
            ->action('Xác nhận Email', $verificationUrl)

            ->line('Cảm ơn bạn đã sử dụng dịch vụ của chúng tôi!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
