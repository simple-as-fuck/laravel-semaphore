<?php

declare(strict_types=1);

namespace SimpleAsFuck\LaravelLock\Factory;

use Symfony\Component\Lock\BlockingStoreInterface;
use Symfony\Component\Lock\Store\SemaphoreStore;

final class SemaphoreFactory extends StoreFactory
{
    public function make(): BlockingStoreInterface
    {
        return new SemaphoreStore();
    }
}
