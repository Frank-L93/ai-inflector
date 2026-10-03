<?php

declare(strict_types=1);

namespace AiInflector\AiInflector\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \AiInflector\AiInflector\AiInflector
 */
class AiInflector extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \AiInflector\AiInflector\AiInflector::class;
    }
}
