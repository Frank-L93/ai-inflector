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

### 1. Inspect the Laravel app context

- confirm the app is a Laravel project
- inspect the target code paths where the package should be applied

### 2. Apply the package's public API

Use the package's inflection API where words need localized singular or plural forms. Cached results can be removed with:

```bash
php artisan ai-inflector:cache:clear
```

## Rules, References, and Templates

Read before executing:

- no additional resource files for this skill

## Examples

- clear stale inflection results without flushing the application's Laravel cache

## Anti-patterns

- do not document package internals here; keep the skill focused on adoption in Laravel apps
