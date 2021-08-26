<?php

declare(strict_types=1);

namespace SimpleAsFuck\LaravelLock\Provider;

use Illuminate\Support\ServiceProvider;

class PackageProvider extends ServiceProvider
{
    public function register(): void
    {
    }

    public function boot(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../../config/lock.php', 'lock');
    }
}
