# Xero Service Provider for Laravel 5

A simple [Laravel 5](http://laravel.com) service provider for including the [PHP Xero API](https://github.com/XeroAPI/PHP-Xero).

No longer maintained
------------
Please note the PHP-Xero wrapper library used by this service provider is no longer under active development.
All development effort is going into the [XeroOAuth-PHP library](https://github.com/XeroAPI/XeroOAuth-PHP).

We will review this after Laravel 5 has been released and likely leave this active (but unsupported) for anyone still using it for their Laravel 4 apps.

## Installation

The Xero Service Provider can be installed via [Composer](http://getcomposer.org) by requiring the `Softlabs/xero-laravel` package in your project's `composer.json`.

```json
{
    "require": {
        "Softlabs/xero-laravel": "3.*"
    },
}
```

Also you need to add the repository to composer.json:

```json
"repositories": [
    {
        "type": "vcs",
        "url": "https://github.com/Softlabs/xero-laravel"
    }
]
```

## Usage

To use the Xero Service Provider, you must register the provider when bootstrapping your Laravel application.

### Use Laravel Configuration

Create a new `config/xero.php` configuration file with the following options:

```php
return [
    'key'           => '<your-xero-key>',
    'secret'        => '<your-xero-secret>',
    'publicPath'    => '../config/xero/publickey.cer',
    'privatePath'   => '../config/xero/privatekey.pem',
    'format'        => 'json'
];
```

Find the `providers` key in `config/app.php` and register the Xero Service Provider.

```php
    'providers' => [
        // ...
        'Softlabs\XeroLaravel\XeroLaravelServiceProvider',
    ]
```

Find the `aliases` key in `config/app.php` and add in our `Xero` alias.

```php
    'aliases' => [
        // ...
        'XeroLaravel'     => 'Softlabs\XeroLaravel\Facades\XeroLaravel',
    ]
```

### Setting up the application

Create public and private keys, and save them in /config/xero/ as publickey.cer and privatekey.pem.

For more info on setting up your keys, check out the [Xero documentation](http://developer.xero.com/documentation/advanced-docs/public-private-keypair/)

## Example Usage

```
$contact = [
    [
        "Name"        => $user['company']['name'],
        "FirstName"   => $user['firstname'],
        "LastName"    => $user['surname'],
    ]
];

$xero_contact = XeroLaravel::Contacts($contact);
```

## Reference

* [PHP Xero API](https://github.com/XeroAPI/PHP-Xero)
* [Laravel website](http://laravel.com)