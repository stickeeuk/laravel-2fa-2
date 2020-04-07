# Stickee Laravel 2FA

This a composer module for adding two factor authentication.

## Contents
 - [Quick Start](#quick-start)
 - [Installation](#installation)
   - [Manual Registration](#manual-registration)
 - [Configuration](#configuration)
   - [Environment Variables](#environment-variables)
   - [Config File](#config-file)
 - [Customisation](#customisation)
 - [Developing](#developing)

## Quick Start

1. `composer require stickee/laravel-2fa`
2. `php artisan migrate`

2FA is now enabled, but not enforced. To enforce it for all users, set in your `.env`:
`LARAVEL_2FA_REQUIRED=true`

Users will be prompted to enroll in 2FA when they log in.

## Installation

`composer require stickee/laravel-2fa`

This module ships with a Laravel service provider which will be automatically registered for Laravel 5.5+.

### Manual registration

The module can be manually registered by adding this to the `providers` array in `config/app.php`:

```
Stickee\Laravel2fa\ServiceProvider::class,
```

## Configuration

### Environment Variables

| Name | Type | Default | Description |
|------|------|---------|-------------|
| LARAVEL_2FA_ENABLED | `bool` | `true` | Used to disable 2FA checking completely |
| LARAVEL_2FA_REQUIRED | `bool` | `false` | Require 2FA for all users |

### Config File

Publish the configuration file to your project with this command:

`php artisan vendor:publish --provider=Stickee\\Laravel2fa\\ServiceProvider --tag=config`

| Name | Type | Default | Description |
|------|------|---------|-------------|
enabled | | `env('LARAVEL_2FA_ENABLED', true)` | Enable or disable 2-factor checking |
user_class | | `\App\User::class` | The user model class |
required | | `env('LARAVEL_2FA_REQUIRED', false)` | If 2FA is required or optional for users (force enrollment) |
middleware_groups | | `['web', 'nova']` | Middleware groups to add 2FA to. It will allways only apply to authenticated users |
routes_middleware | | `['web']` | Middleware to apply to routes used by the 2FA system (e.g. /2fa/register) |
routes_prefix | | `'2fa'` | Prefix for routes used by the 2fa system |
state_store | | `\Stickee\Laravel2fa\StateStores\SessionStateStore::class` | A class implementing `\Stickee\Laravel2fa\Contracts\StateStore` to store state |
session_prefix | | `'laravel-2fa'` | Prefix for variables stored in the session to prevent collisions |
drivers | | `['google' => \Stickee\Laravel2fa\Drivers\Google::class]` | A list of classes implementing \Stickee\Laravel2fa\Contracts\Driver. The key should be the driver name |
qr_code_generator | | `\BaconQrCode\Renderer\Image\SvgImageBackEnd::class` | A backend for Bacon QR Code - one of: `\BaconQrCode\Renderer\Image\ImagickImageBackEnd`, `\BaconQrCode\Renderer\Image\SvgImageBackEnd`, `\BaconQrCode\Renderer\Image\EpsImageBackEnd`. To use a completeley custom generator see [How Do I](#how-do-i) |
qr_code_size | | `400` | The size in pixels of the QR code |
lifetime | | `0` | The number of minutes of inactivity a user is allowed before they must reauthenticate. Set to zero for no limit |
keep_alive | | `true` | Whether to update the last activity time with each pageview. If set to `false` then "lifetime" becomes time since login |
prevent_reuse | | `false` | Prevent the user from logging in twice with the same code |
recovery_codes_count | | `10` | How many recovery codes to generate when a user registers |
redirect_after_login | | `'/'` | was trying to get to) |
redirect_after_logout | | `'/'` | The URL to redirect to after logging out |
app_name | | `null` | The app name to use in the authenticator (use null to use app.name) |
username_attribute | | `'email'` | The attribute on the User model to use as the username in the authenticator |

## Customisation

The package is highly customisable - most parts can be swapped out for your own implementation if required.

### How Do I...?

 - Change the templates
    - Publish the views to your project with `php artisan vendor:publish --provider=Stickee\\Laravel2fa\\ServiceProvider --tag=laravel-views` and edit them there.
 - Customise the text / add a translation
   - Publish the translations to your project with `php artisan vendor:publish --provider=Stickee\\Laravel2fa\\ServiceProvider --tag=translations` and edit them there.
 - Change the QR code generator
    - To change to one of the built-in BaconQrCode backends, change the config variable `qr_code_generator`.
      At the time of writing, supported backends are
      `\BaconQrCode\Renderer\Image\ImagickImageBackEnd::class`
      `\BaconQrCode\Renderer\Image\SvgImageBackEnd::class`
      `\BaconQrCode\Renderer\Image\EpsImageBackEnd::class`
    - To use something completely different, create a class implementing
      `\Stickee\Laravel2fa\Contracts\QrCodeGenerator` and bind it to that interface in your service provider
 - Create a custom driver / provider
   - Implement `\Stickee\Laravel2fa\Contracts\Driver` and register it in your `laravel-2fa.drivers` config. To make things easier you can probably extend `\Stickee\Laravel2fa\Drivers\AbstractDriver`.

## Developing

The easiest way to make changes is to make the project you're importing the module in to load the module from your filesystem instead of the composer repository, like this:

1. `composer remove stickee/laravel-2fa`
2. Edit `composer.json` and add
    ```
    "repositories": [
            {
                "type": "path",
                "url": "../laravel-2fa"
            }
        ]
    ```
    where "../laravel-2fa" is the path to where you have this project checked out
3. `composer require stickee/laravel-2fa`

**NOTE:** Do not check in your `composer.json` like this!
