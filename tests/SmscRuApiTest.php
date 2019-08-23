<?php

namespace NotificationChannel\SmscRu\Tests;

use PHPUnit\Framework\TestCase;
use NotificationChannels\SmscRu\SmscRuApi;

class SmscRuApiTest extends TestCase
{
    public function test_it_has_config_with_default_endpoint()
    {
        $smsc = new SmscRuApi([
            'login'  => $login = 'login',
            'secret' => $secret = 'secret',
            'sender' => $sender = 'sender',
        ]);

        $this->assertAttributeEquals('https://smsc.ru/sys/send.php', 'endpoint', $smsc);
        $this->assertAttributeEquals($login, 'login', $smsc);
        $this->assertAttributeEquals($secret, 'secret', $smsc);
        $this->assertAttributeEquals($sender, 'sender', $smsc);
    }

    public function test_it_has_config_with_custom_endpoint()
    {
        $smsc = new SmscRuApi([
            'host' => $host = 'https://smsc.kz/',
        ]);

        $this->assertAttributeEquals('https://smsc.kz/sys/send.php', 'endpoint', $smsc);
    }
}
