# User activity for Laravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/amdad121/record-activity-laravel.svg?style=flat-square)](https://packagist.org/packages/amdad121/record-activity-laravel)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/amdad121/record-activity-laravel/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/amdad121/record-activity-laravel/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/amdad121/record-activity-laravel/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/amdad121/record-activity-laravel/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/amdad121/record-activity-laravel.svg?style=flat-square)](https://packagist.org/packages/amdad121/record-activity-laravel)

created by, updated by and deleted by added on model of your Laravel.

## Installation

You can install the package via composer:

```bash
composer require amdad121/record-activity-laravel
```

## Usage

```php
<?php

namespace App\Models;

use AmdadulHaq\RecordActivity\RecordActivity;
// ...

class User extends Authenticatable
{
    use RecordActivity;
}
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

-   [Amdadul Haq](https://github.com/amdad121)
-   [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.