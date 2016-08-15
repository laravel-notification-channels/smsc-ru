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
     * @var Dispatcher
     */
    private $events;

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
        $this->events = Mockery::mock(Dispatcher::class);
        $this->message = Mockery::mock(SmscRuMessage::class);
        $this->notification = Mockery::mock(Notification::class);

        app()->instance('events', $this->events);
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

        $this->events->shouldReceive('fire')->twice();
        $this->message->shouldReceive('toArray')->andReturn($data);
        $this->smsc->shouldReceive('send')->with('+1234567890', $data);
        $this->notification->shouldReceive('toSmscRu')->with($notifiable)->andReturn($this->message);


        $this->channel->send($notifiable, $this->notification);
    }

    /** @test */
    public function it_fires_events_while_sending_a_message()
    {
        $this->smsc->shouldReceive('send');
        $this->message->shouldReceive('toArray');
        $this->events->shouldReceive('fire')->twice();
        $this->notification->shouldReceive('toSmscRu')->andReturn($this->message);
        $this->channel->send(new Notifiable, $this->notification);
    }

    /** @test */
    public function it_does_not_send_a_message_when_notifiable_does_not_have_route_notification()
    {
        $this->events->shouldReceive('fire');
        $this->notification->shouldReceive('toSmscRu')->never();
        $this->channel->send(new NotifiableWithoutRouteNotificationForSmscru, $this->notification);
    }

    /** @test */
    public function it_does_not_send_a_message_when_the_event_firing_returns_false()
    {
        $notifiable = Mockery::mock(Notifiable::class);
        $this->events->shouldReceive('fire')->andReturn(false);
        $notifiable->shouldReceive('routeNotificationFor')->never();
        $this->channel->send($notifiable, $this->notification);
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
