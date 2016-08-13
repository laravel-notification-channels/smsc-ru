<?php

namespace NotificationChannels\SmscRu\Exceptions;

use Exception;
use DomainException;

class CouldNotSendNotification extends Exception
{
    public static function serviceRespondedWithAnError(DomainException $exception)
    {
        return new static(
            "Service responded with an error '{$exception->getCode()}: {$exception->getMessage()}'"
        );
    }
}
