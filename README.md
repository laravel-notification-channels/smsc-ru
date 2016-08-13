# Smsc notifications channel for Laravel 5.3 [WIP]

This package makes it easy to send notifications using [Smsc](smsc.ru) (aka СМС–Центр) with Laravel 5.3.

## Contents

- [Installation](#installation)
    - [Setting up the SmscRu service](#setting-up-the-SmscRu-service)
- [Usage](#usage)
    - [Available Message methods](#available-message-methods)
- [Changelog](#changelog)
- [Testing](#testing)
- [Security](#security)
- [Contributing](#contributing)
- [Credits](#credits)
- [License](#license)


## Installation

You can install the package via composer:

```bash
composer require laravel-notification-channels/smscentre
```

You must install the service provider:
```php
// config/app.php
'providers' => [
    ...
    NotificationChannels\SmscRu\SmscRuServiceProvider::class,
];
```

### Setting up the SmscRu service

Add your SmscRu login, secret key (hashed password) and default sender name  to your `config/services.php`:

```php
// config/services.php

'smscentre' => [
    'login'  => env('SMSCENTRE_LOGIN'),
    'secret' => env('SMSCENTRE_SECRET'),
    'sender' => 'John_Doe'
]
```

## Usage

You can use the channel in your `via()` method inside the notification:

```php
use Illuminate\Notifications\Notification;
use NotificationChannels\SmscRu\SmscRuMessage;
use NotificationChannels\SmscRu\SmscRuChannel;

class AccountApproved extends Notification
{
    public function via($notifiable)
    {
        return [SmscRuChannel::class];
    }

    public function toSmscRu($notifiable)
    {
        return (new SmscRuMessage())
            ->content("Your {$notifiable->service} account was approved!");
    }
}
```

### Available methods

TODO

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [JhaoDa](https://github.com/jhaoda)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
