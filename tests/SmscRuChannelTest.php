<?php

namespace NotificationChannel\SmscRu\Tests;

use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification;
use Mockery as M;
use NotificationChannels\SmscRu\SmscRuApi;
use NotificationChannels\SmscRu\SmscRuChannel;
use NotificationChannels\SmscRu\SmscRuMessage;
use PHPUnit\Framework\TestCase;

class SmscRuChannelTest extends TestCase
{
    /** @var SmscRuApi|M\MockInterface */
    private $smsc;

    /** @var SmscRuMessage */
    private $message;

    /** @var SmscRuChannel */
    private $channel;

    /** @var \DateTime */
    public static $sendAt;

    public function setUp(): void
    {
        $this->smsc = M::mock(SmscRuApi::class, [
            'login' => 'test',
            'secret' => 'test',
            'sender' => 'John_Doe',
        ]);
        $this->channel = new SmscRuChannel($this->smsc);
        $this->message = M::mock(SmscRuMessage::class);
    }

    public function tearDown(): void
    {
        M::close();
    }

    public function test_it_can_send_a_notification(): void
    {
        $this->smsc->shouldReceive('send')
            ->once()
            ->with([
                'phones'  => '+1234567890',
                'mes'     => 'hello',
                'sender'  => 'John_Doe',
            ]);

        $this->channel->send(new TestNotifiable(), new TestNotification());
    }

    public function test_it_can_send_a_deferred_notification(): void
    {
        self::$sendAt = new \DateTime();

        $this->smsc->shouldReceive('send')
            ->once()
            ->with([
                'phones'  => '+1234567890',
                'mes'     => 'hello',
                'sender'  => 'John_Doe',
                'time'    => '0'.self::$sendAt->getTimestamp(),
            ]);

        $this->channel->send(new TestNotifiable(), new TestNotificationWithSendAt());
    }

    public function test_it_does_not_send_a_message_when_to_missed(): void
    {
        $this->smsc->shouldNotReceive('send');

        $this->channel->send(
            new TestNotifiableWithoutRouteNotificationForSmscru(), new TestNotification()
        );
    }

    public function test_it_can_send_a_notification_to_multiple_phones(): void
    {
        $this->smsc->shouldReceive('send')
            ->once()
            ->with([
                'phones'  => '+1234567890,+0987654321,+1234554321',
                'mes'     => 'hello',
                'sender'  => 'John_Doe',
            ]);

        $this->channel->send(new TestNotifiableWithManyPhones(), new TestNotification());
    }
}

class TestNotifiable
{
    use Notifiable;

    // Laravel v5.6+ passes the notification instance here
    // So we need to add `Notification $notification` argument to check it when this project stops supporting < 5.6
    public function routeNotificationForSmscru()
    {
        return '+1234567890';
    }
}

class TestNotifiableWithoutRouteNotificationForSmscru extends TestNotifiable
{
    public function routeNotificationForSmscru()
    {
        return false;
    }
}

class TestNotifiableWithManyPhones extends TestNotifiable
{
    public function routeNotificationForSmscru()
    {
        return ['+1234567890', '+0987654321', '+1234554321'];
    }
}

class TestNotification extends Notification
{
    public function toSmscRu()
    {
        return SmscRuMessage::create('hello')->from('John_Doe');
    }
}

class TestNotificationWithSendAt extends Notification
{
    public function toSmscRu()
    {
        return SmscRuMessage::create('hello')
            ->from('John_Doe')
            ->sendAt(SmscRuChannelTest::$sendAt);
    }
}
