# Laravel Log OAuth2 cURL

[![Latest Stable Version](https://poser.pugx.org/richpeers/laravel-log-oauth2-curl/v/stable)](https://packagist.org/packages/richpeers/laravel-log-oauth2-curl)
[![Total Downloads](https://poser.pugx.org/richpeers/laravel-log-oauth2-curl/downloads)](https://packagist.org/packages/richpeers/laravel-log-oauth2-curl)
[![Latest Unstable Version](https://poser.pugx.org/richpeers/laravel-log-oauth2-curl/v/unstable)](https://packagist.org/packages/richpeers/laravel-log-oauth2-curl)
[![License](https://poser.pugx.org/richpeers/laravel-log-oauth2-curl/license)](https://packagist.org/packages/richpeers/laravel-log-oauth2-curl)

Custom Log driver for Laravel **5.6** || **5.7**

Queued and posted via cURL, authorised with (cached) Client Credentials Grant Token.

This package is intended as a client to a server, for recording logs. Useful where you have multiple projects and might have one or more instances. For example develop, test or staging.

## Server requirement

Two endpoints are required at your log server. These are defaults and can be changed in config.
- `/oauth/token`  [Passport](https://laravel.com/docs/5.7/passport)'s default route for granting token response to **client_id** and **client_secret**.
- `/api/logger`&nbsp; POST the logs.

## Installation
Install with composer. The package will automatically register itself.
```
composer require richpeers/laravel-log-oauth2-curl
```

Add environment specific variables. Get the **client_id** and **client_secret** credentials from your log server.
```
LOG_SERVER_HOST=https://your-server.base-url.com
LOG_SERVER_CLIENT_ID=
LOG_SERVER_CLIENT_SECRET=
```

Add the following to the *channels* array in your **/config/logging.php** file.
```
'logserver' => ['driver' => 'logserver']
```

Set default log channel in **.env** ( Or add channel to stack array in /config/logging.php )
```
LOG_CHANNEL=logserver
```

Optionally run this command to copy **logserver.php** to /config, to modify it.
```
php artisan vendor:publish --tag=logserver
```

## Queue Driver
As the package queues the logs before being authenticated and posted to the log server, using something like redis as your queue driver will mean a faster response for the user if there is an error.
