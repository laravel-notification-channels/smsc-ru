<?php

namespace NotificationChannels\SmscRu;

use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification;
use NotificationChannels\SmscRu\Events\SendingMessage;
use NotificationChannels\SmscRu\Events\MessageWasSent;
use NotificationChannels\SmscRu\Exceptions\CouldNotSendNotification;

class SmscRuChannel
{
    protected $smsc;

    public function __construct(SmscRuApi $smsc)
    {
        $this->smsc = $smsc;
    }

    /**
     * Send the given notification.
     *
     * @param  Notifiable    $notifiable
     * @param  Notification  $notification
     *
     * @throws CouldNotSendNotification
     */
    public function send($notifiable, Notification $notification)
    {
        if (! $this->shouldSendMessage($notifiable, $notification)) {
            return;
        }

        if (! $to = $notifiable->routeNotificationFor('smscru')) {
            return;
        }

        /** @var SmscRuMessage $message */
        $message = $notification->toSmscRu($notifiable);

        if (is_string($message)) {
            $message = new SmscRuMessage($message);
        }

        $this->smsc->send($to, $message->toArray());

        event(new MessageWasSent($notifiable, $notification));
    }

    /**
     * Check if we can send the notification.
     *
     * @param  Notifiable    $notifiable
     * @param  Notification  $notification
     *
     * @return bool
     */
    protected function shouldSendMessage($notifiable, $notification)
    {
        return event(new SendingMessage($notifiable, $notification), [], true) !== false;
    }
}
