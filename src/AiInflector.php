<?php

declare(strict_types=1);

namespace AiInflector\AiInflector;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Throwable;

class AiInflector
{
    protected const string CACHE_KEYS_KEY = 'ai_inflector_cache_keys';

    protected const string CACHE_GENERATION_KEY = 'ai_inflector_cache_generation';

    protected string $apiKey;

    protected string $locale;

    protected string $model;

    public function __construct(?string $apiKey = null, ?string $locale = null)
    {
        $configuredApiKey = config('ai-inflector.drivers.gemini.api_key');
        $configuredModel = config('ai-inflector.drivers.gemini.model', 'gemini-2.0-flash');

        $this->apiKey = $apiKey ?? (is_string($configuredApiKey) ? $configuredApiKey : '');
        $this->locale = $locale ?? config('ai-inflector.default_locale', 'nl');
        $this->model = is_string($configuredModel) ? $configuredModel : 'gemini-2.0-flash';
    }

    /**
     * Turn a word into its plural form.
     */
    public function plural(string $word, int $count = 2, ?string $locale = null): string
    {
        if ($count === 1 || empty(trim($word))) {
            return $word;
        }

        $targetLocale = $locale ?? $this->locale;
        $cacheKey = $this->cacheKey('ai_inflector_plural_'.$targetLocale.'_'.Str::slug($word));
        $this->trackCacheKey($cacheKey);

        return Cache::rememberForever($cacheKey, function () use ($word, $targetLocale) {
            return $this->fetchFromAi($word, 'plural', $targetLocale);
        });
    }

    /**
     * Turn a word into its singular form.
     */
    public function singular(string $word, ?string $locale = null): string
    {
        if (empty(trim($word))) {
            return $word;
        }

        $targetLocale = $locale ?? $this->locale;
        $cacheKey = $this->cacheKey('ai_inflector_singular_'.$targetLocale.'_'.Str::slug($word));
        $this->trackCacheKey($cacheKey);

        return Cache::rememberForever($cacheKey, function () use ($word, $targetLocale) {
            return $this->fetchFromAi($word, 'singular', $targetLocale);
        });
    }

    /**
     * Remove cached inflections without flushing the application's cache.
     */
    public function clearCache(): void
    {
        foreach (Cache::get(self::CACHE_KEYS_KEY, []) as $cacheKey) {
            Cache::forget($cacheKey);
        }

        Cache::forget(self::CACHE_KEYS_KEY);
        Cache::forever(self::CACHE_GENERATION_KEY, (int) Cache::get(self::CACHE_GENERATION_KEY, 0) + 1);
    }

    protected function cacheKey(string $cacheKey): string
    {
        $generation = (int) Cache::get(self::CACHE_GENERATION_KEY, 0);

        return $generation === 0 ? $cacheKey : $cacheKey.'_generation_'.$generation;
    }

    protected function trackCacheKey(string $cacheKey): void
    {
        $cacheKeys = Cache::get(self::CACHE_KEYS_KEY, []);

        if (! in_array($cacheKey, $cacheKeys, true)) {
            $cacheKeys[] = $cacheKey;
            Cache::forever(self::CACHE_KEYS_KEY, $cacheKeys);
        }
    }

    /**
     * Executes the API call to the AI model (e.g., Gemini API).
     */
    protected function fetchFromAi(string $word, string $targetForm, string $locale): string
    {
        if (empty($this->apiKey)) {
            return $this->fallback($word, $targetForm);
        }

        try {
            $prompt = "Return ONLY the exact {$targetForm} form of the {$locale} word '{$word}'. Do not include punctuation, markdown, articles (like 'de' or 'het'), or explanations. Lowercase only.";

            $response = Http::withHeaders(['Content-Type' => 'application/json'])
                ->post("https://generativelanguage.googleapis.com/v1beta/models/{$this->model}:generateContent?key={$this->apiKey}", [
                    'contents' => [
                        ['parts' => [['text' => $prompt]]],
                    ],
                    'generationConfig' => [
                        'temperature' => 0.0,
                        'maxOutputTokens' => 10,
                    ],
                ]);

            if ($response->successful()) {
                $result = trim((string) $response->json('candidates.0.content.parts.0.text'));

                return strtolower($result !== '' ? $result : $word);
            }
        } catch (Throwable $e) {
            // Optionally log the error
        }

        return $this->fallback($word, $targetForm);
    }

    protected function fallback(string $word, string $targetForm): string
    {
        return $targetForm === 'singular' ? Str::singular($word) : Str::plural($word);
    }
}
