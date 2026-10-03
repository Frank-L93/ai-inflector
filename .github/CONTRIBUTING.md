# Contributing

Contributions are welcome, including bug reports, documentation improvements, and pull requests. For a substantial change, open an issue first to discuss the problem and proposed approach.

## Development Setup

Ai Inflector supports PHP 8.3+ and Laravel 12 or 13. Fork the repository, clone your fork, and install its development dependencies:

```bash
git clone https://github.com/YOUR-USERNAME/ai-inflector.git
cd ai-inflector
composer install
```

Create a branch for your change before editing:

```bash
git switch -c describe-your-change
```

## Making Changes

- Keep changes focused and follow the existing Laravel and PHP conventions.
- Add or update Pest tests for observable behavior. Prefer testing package APIs, service-provider wiring, Artisan commands, and published resources over implementation details.
- Update the README or other consumer documentation when public behavior, configuration, commands, or examples change.
- Keep Composer constraints and code compatible with PHP 8.3+ and Laravel 12/13. The CI workflow checks multiple PHP versions, dependency stability levels, and Windows.
- Never include API keys, `.env` files, or other secrets in commits or test fixtures.

## Validation

Run the complete validation suite before opening a pull request:

```bash
composer test
```

This runs PHPStan, Pint's formatting check, Pest type coverage, and the test suite. During development, you can run individual checks with:

```bash
composer analyse
composer lint:check
composer test:types
composer test:unit
```

If you change dependencies, verify the Composer files and lockfile are consistent:

```bash
composer validate --strict
```

## Pull Requests

Open a pull request against `main`. In its description, explain the problem and solution, link any related issue, and include the validation commands you ran. Call out behavior changes or compatibility considerations so reviewers can assess their impact.

Keep commits focused and use clear commit messages. If the base branch advances while you work, update your branch before requesting final review. Changes should follow Semantic Versioning; release and publishing actions are handled separately from pull requests.

## Reporting Security Issues

Do not report vulnerabilities in public issues or pull requests. Follow the private reporting process in the [security policy](SECURITY.md).
