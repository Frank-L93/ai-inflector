<?php

declare(strict_types=1);

namespace AiInflector\AiInflector\Console\Commands;

use AiInflector\AiInflector\AiInflector;
use Illuminate\Console\Command;

class AiInflectorCacheClearCommand extends Command
{
    protected $signature = 'ai-inflector:cache:clear';

    protected $description = 'Clear cached AI Inflector results.';

    public function handle(): int
    {
        app(AiInflector::class)->clearCache();

        $this->info('Ai Inflector cache cleared.');

        return self::SUCCESS;
    }
}
