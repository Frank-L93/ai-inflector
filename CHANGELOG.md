# Changelog

All notable changes to Ai Inflector are documented here.

## [Unreleased](https://github.com/frank-l93/ai-inflector/compare/v0.1.0...main)

## [v0.1.0](https://github.com/frank-l93/ai-inflector/compare/75172ce...v0.1.0) - 2026-10-03

First pre-release.

### Added

- AI-powered plural and singular inflection through the Google Gemini API.
- Configurable default locale, Gemini API key, and Gemini model.
- Indefinite caching of inflection results using Laravel's cache.
- `ai-inflector:inflect` Artisan command for plural and singular forms.
- `ai-inflector:cache:clear` Artisan command to clear package results without flushing the application cache.
- Laravel string-helper fallback when the API key is missing or the request fails.
- Laravel package auto-discovery and a publishable configuration file.
