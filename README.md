<div align="center">
    <h1>Ai Inflector</h1>
</div>

<p align="center">
    <a href="https://packagist.org/packages/frank-l93/ai-inflector"><img src="https://img.shields.io/packagist/v/frank-l93/ai-inflector.svg?style=flat-square" alt="Latest version on Packagist"></a>
    <a href="https://packagist.org/packages/frank-l93/ai-inflector"><img src="https://img.shields.io/packagist/php-v/frank-l93/ai-inflector.svg?style=flat-square" alt="Supported PHP versions"></a>
    <a href="https://packagist.org/packages/frank-l93/ai-inflector"><img src="https://badge.laravel.cloud/badge/frank-l93/ai-inflector?style=flat" alt="Laravel versions"></a>
    <a href="https://github.com/frank-l93/ai-inflector/actions/workflows/tests.yml"><img src="https://img.shields.io/github/actions/workflow/status/frank-l93/ai-inflector/tests.yml?branch=main&label=Tests&style=flat-square" alt="Test workflow status"></a>
    <a href="https://packagist.org/packages/frank-l93/ai-inflector"><img src="https://img.shields.io/packagist/dt/frank-l93/ai-inflector.svg?style=flat-square" alt="Total Downloads"></a>
</p>

AI-powered singular and plural inflection for Laravel, using Google Gemini with cached results and a Laravel string fallback.

## Requirements

- PHP 8.3 or later
- Laravel 12 or 13
- A Google Gemini API key for AI-powered inflection

Without an API key, or when the Gemini request fails, the package falls back to Laravel's `Str::plural()` and `Str::singular()` helpers. These fallbacks use Laravel's English inflection rules and may not match the requested locale.

## Installation

Install the package with Composer:

```bash
composer require frank-l93/ai-inflector
```

Laravel discovers the service provider automatically. Set the API key and, optionally, a default locale and Gemini model in your application's `.env` file:

```dotenv
GEMINI_API_KEY=your-gemini-api-key
AI_INFLECTOR_LOCALE=nl
GEMINI_INFLECTOR_MODEL=gemini-2.0-flash
```

The default locale is `nl`, and the default model is `gemini-2.0-flash`. You can keep the package defaults or publish the config file to review and customize them:

```bash
php artisan vendor:publish --tag=ai-inflector-config
```

The published file is `config/ai-inflector.php`. Environment values are read from that config file, so they work with Laravel's config cache.

## Usage

Use the facade to get plural or singular forms. The locale argument overrides the configured default for that call:

```php
use AiInflector\AiInflector\Facades\AiInflector;

$plural = AiInflector::plural('computer', 2, 'nl');
$singular = AiInflector::singular('computers', 'nl');
```

The plural method accepts a count and returns the original word without an API call when the count is `1`. Non-empty results are cached indefinitely in Laravel's default cache store.

The package also provides an Artisan command. Plural is the default type; the command accepts `plural` or `singular` and an optional locale:

```bash
php artisan ai-inflector:inflect computer
php artisan ai-inflector:inflect computers --type=singular --locale=nl
```

Clear cached inflection results without flushing unrelated application cache entries:

```bash
php artisan ai-inflector:cache:clear
```

## Changelog

See [CHANGELOG.md](CHANGELOG.md) for release history.

## Contributing

Bug reports and pull requests are welcome. See the [contribution guide](.github/CONTRIBUTING.md) and run the full validation suite before submitting changes:

```bash
composer test
```

## Security

Please report vulnerabilities privately using the process in the [security policy](.github/SECURITY.md).

## License

Ai Inflector is open-source software licensed under the [MIT License](LICENSE.md).
