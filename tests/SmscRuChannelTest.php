<?php

namespace NotificationChannel\SmscRu\Tests;

use Mockery;
use Orchestra\Testbench\TestCase;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Events\Dispatcher;
use NotificationChannels\SmscRu\SmscRuApi;
use NotificationChannels\SmscRu\SmscRuChannel;
use NotificationChannels\SmscRu\SmscRuMessage;

class SmscRuChannelTest extends TestCase
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

    public function setUp()
    {
        parent::setUp();

        $this->smsc = Mockery::mock(SmscRuApi::class);
        $this->channel = new SmscRuChannel($this->smsc);
        $this->message = Mockery::mock(SmscRuMessage::class);
        $this->notification = Mockery::mock(Notification::class);
    }

    public function tearDown()
    {
        Mockery::close();

        parent::tearDown();
    }

    /** @test */
    public function it_can_send_a_notification()
    {
        $notifiable = new Notifiable;

        $data = [
            'mes'     => 'hello',
            'charset' => 'utf-8',
        ];

        $this->message->shouldReceive('toArray')->andReturn($data);
        $this->smsc->shouldReceive('send')->with('+1234567890', $data);
        $this->notification->shouldReceive('toSmscRu')->with($notifiable)->andReturn($this->message);


        $this->channel->send($notifiable, $this->notification);
    }

    /** @test */
    public function it_does_not_send_a_message_when_notifiable_does_not_have_route_notification()
    {
        $this->notification->shouldReceive('toSmscRu')->never();
        $this->channel->send(new NotifiableWithoutRouteNotificationForSmscru, $this->notification);
    }
}

class Notifiable
{
    public function routeNotificationFor()
    {
        return '+1234567890';
    }
}

class NotifiableWithoutRouteNotificationForSmscru extends Notifiable
{
    public function routeNotificationFor()
    {
        return false;
    }
}
