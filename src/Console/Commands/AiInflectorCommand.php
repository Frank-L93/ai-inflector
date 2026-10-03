<?php

declare(strict_types=1);

namespace AiInflector\AiInflector\Console\Commands;

use AiInflector\AiInflector\AiInflector;
use Illuminate\Console\Command;
use Throwable;

class AiInflectorCommand extends Command
{
    /**
     * The command signature.
     */
    protected $signature = 'ai-inflector:inflect {word} {--type=plural} {--locale=}';

    /**
     * The command description.
     */
    protected $description = 'Command to inflect a word using the AI Inflector.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $word = $this->argument('word');

        if (! is_string($word)) {
            $this->error('The word argument must be a string.');

            return self::FAILURE;
        }

        $typeOption = $this->option('type');
        $type = is_string($typeOption) ? strtolower($typeOption) : 'plural';
        $localeOption = $this->option('locale');
        $defaultLocale = config('ai-inflector.default_locale', 'nl');
        $locale = is_string($localeOption)
            ? $localeOption
            : (is_string($defaultLocale) ? $defaultLocale : 'nl');
        $inflector = app(AiInflector::class);

        $this->info("Calling AI Inflector for '{$word}' ({$type}, language: {$locale})...");

        try {
            $result = match ($type) {
                'singular', 'enkelvoud' => $inflector->singular($word, $locale),
                default => $inflector->plural($word, 2, $locale),
            };

            $this->newLine();
            $this->table(
                ['Input', 'Result', 'Type', 'Language'],
                [[$word, $result, $type, $locale]],
            );

            return self::SUCCESS;
        } catch (Throwable $e) {
            $this->error('There was an error while inflecting the word: '.$e->getMessage());

            return self::FAILURE;
        }
    }
}
