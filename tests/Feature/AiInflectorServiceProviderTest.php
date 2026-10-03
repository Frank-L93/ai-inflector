<?php

declare(strict_types=1);

use AiInflector\AiInflector\AiInflector;

it('registers the inflector as a singleton', function () {
    expect(app(AiInflector::class))->toBeInstanceOf(AiInflector::class)
        ->and(app(AiInflector::class))->toBe(app(AiInflector::class));
});

it('merges the package defaults into application config', function () {
    expect(config('ai-inflector.default_locale'))->toBe('nl')
        ->and(config('ai-inflector.cache.prefix'))->toBe('ai_inflector_');
});
