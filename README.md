# wordpress-rest-api-client

> A Wordpress REST API client for Laravel

[![Travis](https://img.shields.io/travis/mauthi/wordpress-rest-api-client.svg?maxAge=2592000?style=flat-square)](https://travis-ci.org/mauthi/wordpress-rest-api-client)

For when you need to make [Wordpress REST API calls](http://v2.wp-api.org/) from
some other Laravel PHP project, for some reason.

## Installation

This library can be installed with [Composer](https://getcomposer.org):

```text
composer require vnn/wordpress-rest-api-client
```

You need to set your wordpress url in your environment:

```text
WP_REST_API_URL=http://yourwordpress.com
```

## Authentication

For JWT authentication you need the following plugin enabeld in your wordpress installation: [Plugin](https://github.com/WP-API/jwt-auth)

If you get `Authorization header was not found.` you should try the [following](https://developer.wordpress.org/rest-api/frequently-asked-questions/#why-is-authentication-not-working).


## Usage

Example:

```php
use Vnn\WpApiClient\Auth\WpBasicAuth;
use Vnn\WpApiClient\WpClient;

require 'vendor/autoload.php';

$client = new WpClient();
$client->setCredentials(new WpBasicAuth('user', 'securepassword'));

$user = $client->users()->get(2);

print_r($user);
```

## Testing
```bash
composer install
vendor/bin/peridot
```
