<?php

namespace NotificationChannel\SmscRu\Tests;

use NotificationChannels\SmscRu\SmscRuApi;
use PHPUnit\Framework\TestCase;

class SmscRuApiTest extends TestCase
{
    public function test_it_has_config_with_default_endpoint(): void
    {
        $smsc = $this->getExtendedSmscRuApi([
            'login'  => $login = 'login',
            'secret' => $secret = 'secret',
            'sender' => $sender = 'sender',
        ]);

        $this->assertEquals($login, $smsc->getLogin());
        $this->assertEquals($secret, $smsc->getSecret());
        $this->assertEquals($sender, $smsc->getSender());
        $this->assertEquals('https://smsc.ru/sys/send.php', $smsc->getEndpoint());
    }

    public function test_it_has_config_with_custom_endpoint(): void
    {
        $smsc = $this->getExtendedSmscRuApi([
            'host' => $host = 'https://smsc.kz/',
        ]);

        $this->assertEquals('https://smsc.kz/sys/send.php', $smsc->getEndpoint());
    }

    private function getExtendedSmscRuApi(array $config)
    {
        return new class($config) extends SmscRuApi {
            public function getEndpoint(): string
            {
                return $this->endpoint;
            }

            public function getLogin(): string
            {
                return $this->login;
            }

            public function getSecret(): string
            {
                return $this->secret;
            }

            public function getSender(): string
            {
                return $this->sender;
            }
        };
    }
}
