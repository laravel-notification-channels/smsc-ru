<?php

namespace NotificationChannels\SmscRu;

use Illuminate\Notifications\Notification;
use NotificationChannels\SmscRu\Events\SendingMessage;

class SmscRuChannel
{
    /** @var \NotificationChannels\SmscRu\SmscRuApi */
    protected $smsc;

    public function __construct(SmscRuApi $smsc)
    {
        $this->smsc = $smsc;
    }

    /**
     * Send the given notification.
     *
     * @param  mixed  $notifiable
     * @param  \Illuminate\Notifications\Notification  $notification
     *
     * @throws  \NotificationChannels\SmscRu\Exceptions\CouldNotSendNotification
     */
    public function send($notifiable, Notification $notification)
    {
        if (! $to = $notifiable->routeNotificationFor('smscru')) {
            return;
        }

        $message = $notification->toSmscRu($notifiable);

        if (is_string($message)) {
            $message = new SmscRuMessage($message);
        }

        $this->smsc->send($to, $message->toArray());
    }

    /**
     * Check if we can send the notification.
     *
     * @param      $notifiable
     * @param \Illuminate\Notifications\Notification $notification
     *
     * @return bool
     */
    protected function shouldSendMessage($notifiable, Notification $notification)
    {
        return event(new SendingMessage($notifiable, $notification), [], true) !== false;
    }
}
