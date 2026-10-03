<?php

declare(strict_types=1);

use AiInflector\AiInflector\AiInflector;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

it('runs the inflect command using the default plural type and locale', function () {
    config([
        'ai-inflector.drivers.gemini.api_key' => 'test-key',
        'ai-inflector.default_locale' => 'nl',
    ]);
    Http::fake([
        'generativelanguage.googleapis.com/*' => Http::response([
            'candidates' => [['content' => ['parts' => [['text' => 'computers']]]]],
        ]),
    ]);

    $this->artisan('ai-inflector:inflect computer')
        ->expectsOutputToContain('computers')
        ->assertSuccessful();
});

it('runs the inflect command with the singular type and requested locale', function () {
    config(['ai-inflector.drivers.gemini.api_key' => 'test-key']);
    Http::fake([
        'generativelanguage.googleapis.com/*' => Http::response([
            'candidates' => [['content' => ['parts' => [['text' => 'computer']]]]],
        ]),
    ]);

    $this->artisan('ai-inflector:inflect computers --type=singular --locale=fr')
        ->expectsOutputToContain('computer')
        ->assertSuccessful();
});

it('clears only inflector cache entries from the cache command', function () {
    app(AiInflector::class)->plural('computer', 2, 'nl');
    Cache::forever('ai_inflector_plural_nl_mouse', 'stale cached value');
    Cache::forever('unrelated_cache_entry', 'keep me');

    $this->artisan('ai-inflector:cache:clear')
        ->expectsOutputToContain('Ai Inflector cache cleared.')
        ->assertSuccessful();

    expect(Cache::has('ai_inflector_plural_nl_computer'))->toBeFalse()
        ->and(app(AiInflector::class)->plural('mouse', 2, 'nl'))->not->toBe('stale cached value')
        ->and(Cache::get('unrelated_cache_entry'))->toBe('keep me');
});
