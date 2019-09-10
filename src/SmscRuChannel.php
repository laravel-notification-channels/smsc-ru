<?php

namespace NotificationChannels\SmscRu;

use Illuminate\Notifications\Notification;
use NotificationChannels\SmscRu\Exceptions\CouldNotSendNotification;

class SmscRuChannel
{
    /** @var SmscRuApi */
    protected $smsc;

    public function __construct(SmscRuApi $smsc)
    {
        $this->smsc = $smsc;
    }

    /**
     * Send the given notification.
     *
     * @param  mixed  $notifiable
     * @param  Notification  $notification
     *
     * @return void
     */
    public function send($notifiable, Notification $notification): void
    {
        if (! ($to = $this->getRecipients($notifiable, $notification))) {
            return;
        }

        $message = $notification->{'toSmscRu'}($notifiable);

        if (\is_string($message)) {
            $message = new SmscRuMessage($message);
        }

        $this->sendMessage($to, $message);
    }

    /**
     * Gets a list of phones from the given notifiable.
     *
     * @param  mixed  $notifiable
     * @param  Notification  $notification
     *
     * @return string[]
     */
    protected function getRecipients($notifiable, Notification $notification): array
    {
        $to = $notifiable->routeNotificationFor('smscru', $notification);

        if ($to === null || $to === false || $to === '') {
            return [];
        }

        return \is_array($to) ? $to : [$to];
    }

    protected function sendMessage($recipients, SmscRuMessage $message): void
    {
        if (\mb_strlen($message->content) > 800) {
            throw CouldNotSendNotification::contentLengthLimitExceeded();
        }

        $params = [
            'phones'  => \implode(',', $recipients),
            'mes'     => $message->content,
            'sender'  => $message->from,
        ];

        if ($message->sendAt instanceof \DateTimeInterface) {
            $params['time'] = '0'.$message->sendAt->getTimestamp();
        }

        $this->smsc->send($params);
    }
}
