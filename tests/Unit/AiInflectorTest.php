<?php

declare(strict_types=1);

use AiInflector\AiInflector\AiInflector;
use Illuminate\Http\Client\Request as ClientRequest;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

it('returns a word unchanged for a singular count without making an API request', function () {
    Http::fake();

    expect((new AiInflector('test-key', 'nl'))->plural('child', 1))->toBe('child');

    Http::assertNothingSent();
});

it('returns blank words unchanged without making an API request', function () {
    Http::fake();

    expect((new AiInflector('test-key', 'nl'))->plural('   '))->toBe('   ')
        ->and((new AiInflector('test-key', 'nl'))->singular(''))->toBe('');

    Http::assertNothingSent();
});

it('returns a trimmed lowercase plural from the AI response', function () {
    Http::fake([
        'generativelanguage.googleapis.com/*' => Http::response([
            'candidates' => [[
                'content' => [
                    'parts' => [['text' => '  COMPUTERS  ']],
                ],
            ]],
        ]),
    ]);

    expect((new AiInflector('test-key', 'nl'))->plural('computer'))->toBe('computers');

    Http::assertSent(fn (ClientRequest $request): bool => $request->method() === 'POST'
        && str_contains($request->url(), 'key=test-key')
        && $request->data()['contents'][0]['parts'][0]['text'] === "Return ONLY the exact plural form of the nl word 'computer'. Do not include punctuation, markdown, articles (like 'de' or 'het'), or explanations. Lowercase only.",
    );
});

it('uses the configured Gemini key and model', function () {
    config([
        'ai-inflector.drivers.gemini.api_key' => 'configured-key',
        'ai-inflector.drivers.gemini.model' => 'gemini-test-model',
    ]);
    Http::fake([
        'generativelanguage.googleapis.com/*' => Http::response([
            'candidates' => [[
                'content' => [
                    'parts' => [['text' => 'computers']],
                ],
            ]],
        ]),
    ]);

    expect((new AiInflector)->plural('computer'))->toBe('computers');

    Http::assertSent(fn (ClientRequest $request): bool => str_contains($request->url(), 'models/gemini-test-model:generateContent?key=configured-key'),
    );
});

it('caches a successful plural response', function () {
    Http::fake([
        'generativelanguage.googleapis.com/*' => Http::response([
            'candidates' => [[
                'content' => [
                    'parts' => [['text' => 'computers']],
                ],
            ]],
        ]),
    ]);

    $inflector = new AiInflector('test-key', 'nl');

    expect($inflector->plural('computer'))->toBe('computers')
        ->and($inflector->plural('computer'))->toBe('computers');

    Http::assertSentCount(1);
});

it('returns a singular form from the AI response', function () {
    Http::fake([
        'generativelanguage.googleapis.com/*' => Http::response([
            'candidates' => [[
                'content' => [
                    'parts' => [['text' => '  COMPUTER  ']],
                ],
            ]],
        ]),
    ]);

    expect((new AiInflector('test-key', 'nl'))->singular('computers'))->toBe('computer');

    Http::assertSent(fn (ClientRequest $request): bool => str_contains($request->data()['contents'][0]['parts'][0]['text'], 'exact singular form'),
    );
});

it('uses separate cache entries for different locales', function () {
    Http::fakeSequence()
        ->push(['candidates' => [['content' => ['parts' => [['text' => 'computers-nl']]]]]])
        ->push(['candidates' => [['content' => ['parts' => [['text' => 'computers-en']]]]]]);

    $inflector = new AiInflector('test-key', 'nl');

    expect($inflector->plural('computer', 2, 'nl'))->toBe('computers-nl')
        ->and($inflector->plural('computer', 2, 'en'))->toBe('computers-en');

    Http::assertSentCount(2);
});

it('falls back to Laravel pluralization when no API key is configured', function () {
    Http::fake();

    expect((new AiInflector('', 'nl'))->plural('computer'))->toBe(Str::plural('computer'));

    Http::assertNothingSent();
});

it('falls back to Laravel pluralization when the API request fails', function () {
    Http::fake([
        'generativelanguage.googleapis.com/*' => Http::response([], 503),
    ]);

    expect((new AiInflector('test-key', 'nl'))->plural('computer'))->toBe(Str::plural('computer'));
});

it('falls back to Laravel singularization when no API key is configured', function () {
    Http::fake();

    expect((new AiInflector('', 'nl'))->singular('computers'))->toBe(Str::singular('computers'));

    Http::assertNothingSent();
});

it('returns the original word when a successful response has no text', function () {
    Http::fake([
        'generativelanguage.googleapis.com/*' => Http::response([
            'candidates' => [['content' => ['parts' => [['text' => '']]]]],
        ]),
    ]);

    expect((new AiInflector('test-key', 'nl'))->singular('COMPUTERS'))->toBe('computers');
});

it('forgets tracked entries and invalidates old entries without flushing unrelated cache', function () {
    $inflector = new AiInflector('', 'nl');
    $inflector->plural('computer');
    Cache::forever('ai_inflector_plural_nl_mouse', 'stale cached value');
    Cache::forever('unrelated_cache_entry', 'keep me');

    $inflector->clearCache();

    expect(Cache::has('ai_inflector_plural_nl_computer'))->toBeFalse()
        ->and($inflector->plural('mouse'))->not->toBe('stale cached value')
        ->and(Cache::get('unrelated_cache_entry'))->toBe('keep me');
});
