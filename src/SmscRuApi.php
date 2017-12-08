<?php

namespace NotificationChannels\SmscRu;

use DomainException;
use GuzzleHttp\Client as HttpClient;
use NotificationChannels\SmscRu\Exceptions\CouldNotSendNotification;

class SmscRuApi
{
    const FORMAT_JSON = 3;

    /** @var HttpClient */
    protected $httpClient;

    /** @var string */
    protected $url;

    /** @var string */
    protected $login;

    /** @var string */
    protected $secret;

    /** @var string */
    protected $sender;

    public function __construct($config)
    {
        $this->url      = array_get($config, 'host', 'https://smsc.ru/') . 'sys/send.php';
        $this->login    = array_get($config, 'login');
        $this->secret   = array_get($config, 'secret');
        $this->sender   = array_get($config, 'sender');

        $this->httpClient = new HttpClient([
            'timeout' => 5,
            'connect_timeout' => 5,
        ]);
    }

    /**
     * @param  array  $params
     *
     * @return array
     *
     * @throws CouldNotSendNotification
     */
    public function send($params)
    {
        $base = [
            'charset' => 'utf-8',
            'login'   => $this->login,
            'psw'     => $this->secret,
            'sender'  => $this->sender,
            'fmt'     => self::FORMAT_JSON,
        ];

        $params = array_merge($params, $base);

        try {
            $response = $this->httpClient->post($this->url, ['form_params' => $params]);

            $response = json_decode((string) $response->getBody(), true);

            if (isset($response['error'])) {
                throw new DomainException($response['error'], $response['error_code']);
            }

            return $response;
        } catch (DomainException $exception) {
            throw CouldNotSendNotification::smscRespondedWithAnError($exception);
        } catch (\Exception $exception) {
            throw CouldNotSendNotification::couldNotCommunicateWithSmsc($exception);
        }
    }
}
