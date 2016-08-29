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
     * @var Notification
     */
    private $notification;

//    public function setUp()
//    {
//        parent::setUp();
//
//        $this->smsc = Mockery::mock(SmscRuApi::class);
//        $this->channel = new SmscRuChannel($this->smsc);
//        $this->message = Mockery::mock(SmscRuMessage::class);
//    }
//
//    public function tearDown()
//    {
//        Mockery::close();
//    }

    /** @test */
    public function it_can_send_a_notification()
    {
        $smsc = M::mock(SmscRuApi::class, ['test', 'test', 'John_Doe']);

        $smsc->shouldReceive('send')->once()
            ->with(
                [
                    'phones'  => '+1234567890',
                    'mes'     => 'hello',
                    'sender'  => 'John_Doe',
                ]
            );

        $channel = new SmscRuChannel($smsc);
        $channel->send(new TestNotifiable(), new TestNotification());
    }

    /** @test */
    public function it_does_not_send_a_message_when_to_missed()
    {
        $this->expectException(CouldNotSendNotification::class);

        $channel = new SmscRuChannel(M::mock(SmscRuApi::class));
        $channel->send(new TestNotifiableWithoutRouteNotificationForSmscru(), new TestNotification());
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
