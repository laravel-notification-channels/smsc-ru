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
    public static $timestamp;

    /**
     * @var \DateTimeZone
     */
    public static $timezone;

    public function setUp()
    {
        parent::setUp();

        $this->smsc = M::mock(SmscRuApi::class, ['test', 'test', 'John_Doe']);
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
    public function it_can_send_a_notification_with_time_and_timestamp()
    {
        self::$timestamp = date_create();
        self::$timezone = timezone_open('Europe/Moscow');

        $this->smsc->shouldReceive('send')->once()
            ->with(
                [
                    'phones'  => '+1234567890',
                    'mes'     => 'hello',
                    'sender'  => 'John_Doe',
                    'time'    => '0'.self::$timestamp->getTimestamp(),
                    'tz'      => 0,
                ]
            );

        $this->channel->send(new TestNotifiable(), new TestNotificationWithTimestampAndTimezone());
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

class TestNotificationWithTimestampAndTimezone extends Notification
{
    public function toSmscRu()
    {
        return SmscRuMessage::create('hello')->from('John_Doe')
            ->timestamp(SmscRuChannelTest::$timestamp)
            ->timezone(SmscRuChannelTest::$timezone);
    }
}
