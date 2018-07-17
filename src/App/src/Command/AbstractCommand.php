<?php declare(strict_types=1);

namespace App\Command;

abstract class AbstractCommand
{
    
    public function getHandlerClassName()
    {
        return end(explode('\\', static::class)).'Handler';
    }
    
}
