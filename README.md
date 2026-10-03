<div align="center">
    <h1>Ai Inflector</h1>
</div>

<p align="center">
    <a href="https://packagist.org/packages/frank-l93/ai-inflector"><img src="https://img.shields.io/packagist/v/frank-l93/ai-inflector.svg?style=flat-square" alt="Packagist"></a>
    <a href="https://packagist.org/packages/frank-l93/ai-inflector"><img src="https://img.shields.io/packagist/php-v/frank-l93/ai-inflector.svg?style=flat-square" alt="PHP from Packagist"></a>
    <a href="https://packagist.org/packages/frank-l93/ai-inflector"><img src="https://badge.laravel.cloud/badge/frank-l93/ai-inflector?style=flat" alt="Laravel versions"></a>
    <a href="https://github.com/frank-l93/ai-inflector/actions"><img alt="GitHub Workflow Status (main)" src="https://img.shields.io/github/actions/workflow/status/frank-l93/ai-inflector/tests.yml?branch=main&label=Tests&style=flat-square"></a>
    <a href="https://packagist.org/packages/frank-l93/ai-inflector"><img src="https://img.shields.io/packagist/dt/frank-l93/ai-inflector.svg?style=flat-square" alt="Total Downloads"></a>
</p>

AI-powered singular and plural inflector with automatic caching for Laravel.

## Installation

You can install the package via Composer:

```bash
composer require frank-l93/ai-inflector
```

You may publish all of the package's resources at once:

```bash
php artisan vendor:publish --tag="ai-inflector"
```

Or, you may publish each resource individually:

### Publishing the Configuration File

```bash
php artisan vendor:publish --tag="ai-inflector-config"
```

## Usage

<!-- Add a basic usage example here. -->

Clear cached inflection results without clearing the rest of the Laravel cache:

```bash
php artisan ai-inflector:cache:clear
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Thank you for considering contributing to Ai Inflector! Please review our [contributing guide](.github/CONTRIBUTING.md) to get started.

## Security Vulnerabilities

Please review [our security policy](.github/SECURITY.md) on how to report security vulnerabilities.

## Credits

- [Frank-L93](https://github.com/frank-l93)
- [All Contributors](../../contributors)

## License

Ai Inflector is open-sourced software licensed under the [MIT license](LICENSE.md).
