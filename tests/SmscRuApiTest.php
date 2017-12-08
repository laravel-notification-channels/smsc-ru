<?php

namespace NotificationChannel\SmscRu\Tests;

use Mockery as M;
use Illuminate\Notifications\Notification;
use NotificationChannels\SmscRu\SmscRuApi;
use NotificationChannels\SmscRu\SmscRuChannel;
use NotificationChannels\SmscRu\SmscRuMessage;
use NotificationChannels\SmscRu\Exceptions\CouldNotSendNotification;

class SmscRuApiTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var SmscRuApi
     */
    private $smsc;

    public function tearDown()
    {
        $this->smsc = null;

        parent::tearDown();
    }

    /** @test */
    public function it_has_default_url()
    {
        $this->smsc = new SmscRuApi([]);
        $this->assertAttributeEquals('https://smsc.ru/sys/send.php', 'url', $this->smsc);
        $this->assertAttributeEquals(null, 'login', $this->smsc);
        $this->assertAttributeEquals(null, 'secret', $this->smsc);
        $this->assertAttributeEquals(null, 'sender', $this->smsc);
    }

    /** @test */
    public function it_has_custom_config()
    {
        $host   = 'https://smsc.kz/';
        $login  = 'login';
        $secret = 'secret';
        $sender = 'sender';

        $this->smsc = new SmscRuApi([
            'host'   => $host,
            'login'  => $login,
            'secret' => $secret,
            'sender' => $sender
        ]);

        $this->assertAttributeEquals('https://smsc.kz/sys/send.php', 'url', $this->smsc);
        $this->assertAttributeEquals($login, 'login', $this->smsc);
        $this->assertAttributeEquals($secret, 'secret', $this->smsc);
        $this->assertAttributeEquals($sender, 'sender', $this->smsc);
    }

}
