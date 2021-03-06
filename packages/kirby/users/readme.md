# Users

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Total Downloads][ico-downloads]][link-downloads]
[![Build Status][ico-travis]][link-travis]
[![StyleCI][ico-styleci]][link-styleci]

This is where your description should go. Take a look at [contributing.md](contributing.md) to see a to do list.

## Installation

Via Composer

``` bash
composer require kirby/users
```

## Setup

Adjust the `config/auth.php` file to:

```php
'providers' => [
    'users' => [
        'driver' => 'eloquent',
        'model'  => \Kirby\Users\Models\User::class,
    ],
]
```

## Usage

## Change log

Please see the [changelog](changelog.md) for more information on what has changed recently.

## Testing

``` bash
$ codecept run
```

## Contributing

Please see [contributing.md](contributing.md) for details and a todo list.

## Security

If you discover any security related issues, please email author email instead of using the issue tracker.

## Credits

- [Johan Alvarez](https://github.com/llstarscreamll)


## License

Please see the [license file](license.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/llstarscreamll/laravel-users.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/llstarscreamll/laravel-users.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/llstarscreamll/laravel-users/master.svg?style=flat-square
[ico-styleci]: https://styleci.io/repos/12345678/shield

[link-packagist]: https://packagist.org/packages/llstarscreamll/laravel-users
[link-downloads]: https://packagist.org/packages/llstarscreamll/laravel-users
[link-travis]: https://travis-ci.org/llstarscreamll/laravel-users
[link-styleci]: https://styleci.io/repos/12345678
[https://github.com/llstarscreamll]: https://github.com/llstarscreamll
[link-contributors]: ../../contributors]