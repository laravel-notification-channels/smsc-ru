<?php

namespace NotificationChannels\SmscRu;

use Illuminate\Notifications\Notification;
use NotificationChannels\SmscRu\Exceptions\CouldNotSendNotification;

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
        $to = $notifiable->routeNotificationFor('smscru');

        if (empty($to)) {
            throw CouldNotSendNotification::missingRecipient();
        }

        $message = $notification->toSmscRu($notifiable);

        if (is_string($message)) {
            $message = new SmscRuMessage($message);
        }

        $this->sendMessage($to, $message);
    }

    protected function sendMessage($recipient, SmscRuMessage $message)
    {
        if (\mb_strlen($message->content) > 800) {
            throw CouldNotSendNotification::contentLengthLimitExceeded();
        }

        $params = [
            'phones'  => $recipient,
            'mes'     => $message->content,
            'sender'  => $message->from,
        ];

        if ($message->sendAt instanceof \DateTimeInterface) {
            $params['time'] = '0'.$message->sendAt->getTimestamp();
        }

        $this->smsc->send($params);
    }
}
