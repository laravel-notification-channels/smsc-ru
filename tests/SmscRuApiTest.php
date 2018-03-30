<?php

namespace NotificationChannel\SmscRu\Tests;

use NotificationChannels\SmscRu\SmscRuApi;

class SmscRuApiTest extends \PHPUnit_Framework_TestCase
{
    /** @var SmscRuApi */
    private $smsc;

    public function tearDown()
    {
        $this->smsc = null;

        parent::tearDown();
    }

    /** @test */
    public function it_has_config_with_default_endpoint()
    {
        $this->smsc = new SmscRuApi([
            'login'  => $login = 'login',
            'secret' => $secret = 'secret',
            'sender' => $sender = 'sender',
        ]);

        $this->assertAttributeEquals('https://smsc.ru/sys/send.php', 'endpoint', $this->smsc);
        $this->assertAttributeEquals($login, 'login', $this->smsc);
        $this->assertAttributeEquals($secret, 'secret', $this->smsc);
        $this->assertAttributeEquals($sender, 'sender', $this->smsc);
    }

    /** @test */
    public function it_has_config_with_custom_endpoint()
    {
        $this->smsc = new SmscRuApi([
            'host' => $host = 'https://smsc.kz/',
        ]);

        $this->assertAttributeEquals('https://smsc.kz/sys/send.php', 'endpoint', $this->smsc);
    }
}
