<?php

namespace NotificationChannels\SmscRu;

use GuzzleHttp\Client as HttpClient;
use Illuminate\Support\Arr;
use NotificationChannels\SmscRu\Exceptions\CouldNotSendNotification;

class SmscRuApi
{
    public const FORMAT_JSON = 3;

    /** @var HttpClient */
    protected $client;

    /** @var string */
    protected $endpoint;

    /** @var string */
    protected $login;

    /** @var string */
    protected $secret;

    /** @var string */
    protected $sender;

    /** @var array */
    protected $extra;

    public function __construct(array $config)
    {
        $this->login = Arr::get($config, 'login');
        $this->secret = Arr::get($config, 'secret');
        $this->sender = Arr::get($config, 'sender');
        $this->endpoint = Arr::get($config, 'host', 'https://smsc.ru/').'sys/send.php';

        $this->extra = Arr::get($config, 'extra', []);

        $this->client = new HttpClient([
            'timeout' => 5,
            'connect_timeout' => 5,
        ]);
    }

    public function send($params)
    {
        $base = [
            'charset' => 'utf-8',
            'login'   => $this->login,
            'psw'     => $this->secret,
            'sender'  => $this->sender,
            'fmt'     => self::FORMAT_JSON,
        ];

        $params = \array_merge($base, \array_filter($params), $this->extra);

        try {
            $response = $this->client->request('POST', $this->endpoint, ['form_params' => $params]);

            $response = \json_decode((string) $response->getBody(), true);

            if (isset($response['error'])) {
                throw new \DomainException($response['error'], $response['error_code']);
            }

            return $response;
        } catch (\DomainException $exception) {
            throw CouldNotSendNotification::smscRespondedWithAnError($exception);
        } catch (\Exception $exception) {
            throw CouldNotSendNotification::couldNotCommunicateWithSmsc($exception);
        }
    }
}
