<?php

namespace App\Notifications;

use App\UserFile;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\SlackMessage;

class FileProcessedNotification extends Notification
{
    use Queueable;

    /**
     * @var UserFile
     */
    protected $file;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(UserFile $file)
    {
        $this->file = $file;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['slack'];
    }

    /**
     * Get the Slack representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return SlackMessage
     */
    public function toSlack($notifiable)
    {
        return (new SlackMessage)
            ->to('#rebel-client-launch')
            ->content("{$this->file->name} processed successfully.");
    }
}
