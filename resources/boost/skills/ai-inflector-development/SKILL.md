---
name: ai-inflector-development
description: >
  Configure and apply the Ai Inflector package in Laravel applications.
license: MIT
metadata:
  author: Frank-L93
---

# Ai Inflector

Use this skill when a Laravel application needs to integrate the Ai Inflector package.

## Primary Goal

- apply the `frank-l93/ai-inflector` package's public API in the smallest correct way

## Workflow

### 1. Configure the package

- install `frank-l93/ai-inflector` with Composer
- set `GEMINI_API_KEY` and optionally `AI_INFLECTOR_LOCALE` or `GEMINI_INFLECTOR_MODEL`
- publish `ai-inflector-config` only when the application's config file needs customization

### 2. Inflect words

Use the facade for plural or singular forms:

```php
use AiInflector\AiInflector\Facades\AiInflector;

$plural = AiInflector::plural('computer', 2, 'nl');
$singular = AiInflector::singular('computers', 'nl');
```

The `ai-inflector:inflect` command can also inflect a word from the terminal. Cached results can be removed with:

```bash
php artisan ai-inflector:cache:clear
```

## Rules, References, and Templates

Read before executing:

- no additional resource files for this skill

## Examples

- use the facade for application code that needs plural or singular word forms
- run `php artisan ai-inflector:inflect computer --type=plural --locale=nl` for a one-off inflection
- clear stale inflection results without flushing the application's Laravel cache

## Anti-patterns

- do not document package internals here; keep the skill focused on adoption in Laravel apps
