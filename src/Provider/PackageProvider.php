<?php

declare(strict_types=1);

namespace SimpleAsFuck\LaravelLock\Provider;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Support\ServiceProvider;
use SimpleAsFuck\LaravelLock\Factory\FlockFactory;
use SimpleAsFuck\LaravelLock\Factory\PostgreSqlFactory;
use SimpleAsFuck\LaravelLock\Factory\SemaphoreFactory;
use SimpleAsFuck\LaravelLock\Factory\StoreFactory;
use SimpleAsFuck\LaravelLock\Service\LockManager;
use SimpleAsFuck\Validator\Factory\Validator;
use Symfony\Component\Lock\LockFactory;

class PackageProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(LockManager::class);

        $this->app->singleton(LockFactory::class, function (): LockFactory {
            /** @var Repository $config */
            $config = $this->app->make(Repository::class);
            $storeName = Validator::make($config->get('lock.store'))->string()->notNull();

            /** @var array<string, StoreFactory> $storeFactories */
            $storeFactories = [
                'semaphore' => new SemaphoreFactory(),
                'flock' => new FlockFactory(),
                'pgsql' => $this->app->make(PostgreSqlFactory::class),
            ];

            if (! array_key_exists($storeName, $storeFactories)) {
                throw new \RuntimeException('Factory for lock store: "'.$storeName.'" not found, check "LOCK_STORE" env value or "lock.store" config value');
            }

            return new LockFactory($storeFactories[$storeName]->make());
        });
    }

    public function boot(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../../config/lock.php', 'lock');
    }
}
