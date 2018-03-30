<?php

namespace NotificationChannel\SmscRu\Tests;

use Mockery as M;
use Illuminate\Notifications\Notification;
use NotificationChannels\SmscRu\SmscRuApi;
use NotificationChannels\SmscRu\SmscRuChannel;
use NotificationChannels\SmscRu\SmscRuMessage;
use NotificationChannels\SmscRu\Exceptions\CouldNotSendNotification;

class SmscRuChannelTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var SmscRuApi
     */
    private $smsc;

    /**
     * @var SmscRuMessage
     */
    private $message;

    /**
     * @var SmscRuChannel
     */
    private $channel;

    /**
     * @var \DateTime
     */
    public static $sendAt;

    public function setUp()
    {
        parent::setUp();

        $this->smsc = M::mock(SmscRuApi::class, [
            'login' => 'test',
            'secret' => 'test',
            'sender' => 'John_Doe',
        ]);
        $this->channel = new SmscRuChannel($this->smsc);
        $this->message = M::mock(SmscRuMessage::class);
    }

    public function tearDown()
    {
        M::close();

        parent::tearDown();
    }

    /** @test */
    public function it_can_send_a_notification()
    {
        $this->smsc->shouldReceive('send')->once()
            ->with(
                [
                    'phones'  => '+1234567890',
                    'mes'     => 'hello',
                    'sender'  => 'John_Doe',
                ]
            );

        $this->channel->send(new TestNotifiable(), new TestNotification());
    }

    /** @test */
    public function it_can_send_a_deferred_notification()
    {
        self::$sendAt = new \DateTime();

        $this->smsc->shouldReceive('send')->once()
            ->with(
                [
                    'phones'  => '+1234567890',
                    'mes'     => 'hello',
                    'sender'  => 'John_Doe',
                    'time'    => '0'.self::$sendAt->getTimestamp(),
                ]
            );

        $this->channel->send(new TestNotifiable(), new TestNotificationWithSendAt());
    }

    /** @test */
    public function it_does_not_send_a_message_when_to_missed()
    {
        $this->expectException(CouldNotSendNotification::class);

        $this->channel->send(
            new TestNotifiableWithoutRouteNotificationForSmscru(), new TestNotification()
        );
    }
}

class TestNotifiable
{
    public function routeNotificationFor()
    {
        return '+1234567890';
    }
}

class TestNotifiableWithoutRouteNotificationForSmscru extends TestNotifiable
{
    public function routeNotificationFor()
    {
        return false;
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
