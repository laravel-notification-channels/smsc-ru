# Changelog

All notable changes to `smsc-ru` will be documented in this file

## 3.1.0 - 2020-09-12

- Updated to support Laravel 8

## 3.0.3 - 2020-03-05

- Updated to support Laravel 7

## 3.0.2, 2.0.4 - 2019-09-26

- Extra API parameters via config
- Return API response from `send()`

## 3.0.1 - 2019-09-12

Fix "Unresolvable dependency resolving"

## 3.0.0 - 2019-09-10

- Laravel 6.0 & PHP 7.2

## 2.0.3 - 2019-08-23

- Fix wrong parameters error in CouldNotSendNotification ([#33](https://github.com/laravel-notification-channels/smsc-ru/issues/33))

## 2.0.2 - 2019-04-24

- Updated to support Laravel 5.8

## 2.0.1 - 2018-09-27

- Updated to support Laravel 5.7
- Add code and previous exception to exceptions thrown via `smscRespondedWithAnError` and `couldNotCommunicateWithSmsc`

## 2.0.0 - 2018-06-10

- `routeNotificationForSmscru` can return array of phone numbers ([#26](https://github.com/laravel-notification-channels/smsc-ru/issues/26), [#27](https://github.com/laravel-notification-channels/smsc-ru/pull/27))
- No exception thrown if `routeNotificationForSmscru` returns empty value ([#26](https://github.com/laravel-notification-channels/smsc-ru/issues/26), [#27](https://github.com/laravel-notification-channels/smsc-ru/pull/27))

## 1.1.6 - 2018-03-30

- Close [#12](https://github.com/laravel-notification-channels/smsc-ru/issues/12)

## 1.1.5 - 2018-03-15

- Fix [#24](https://github.com/laravel-notification-channels/smsc-ru/issues/24)

## 1.1.4 - 2018-02-10

- Updated to support Laravel 5.6
- Fix [#21](https://github.com/laravel-notification-channels/smsc-ru/issues/21)

## 1.1.3 - 2018-02-07

- Fix [#19](https://github.com/laravel-notification-channels/smsc-ru/issues/19)

## 1.1.2 - 2017-08-31

- Updated to support Laravel 5.5

## 1.1.1 - 2017-06-20

- Fix notification routing method name (`routeNotificationForSmsru` → `routeNotificationForSmscru`)

## 1.1.0 - 2017-05-23

- `sendAt()` method added

## 1.0.2 - 2017-04-17

- Get 'sender' from settings when sending message without calling `from` method

## 1.0.1 - 2017-02-23

- Updated to support Laravel 5.4

## 1.0.0 - 2016-08-29

- Refactoring
- Tests improvements
- Use stable version and allow backport usage

## 0.0.2 - 2016-08-13

- experimental release

## 0.0.1 - 2016-08-13

- experimental release
