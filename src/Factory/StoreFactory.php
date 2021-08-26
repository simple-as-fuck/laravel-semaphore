<?php

declare(strict_types=1);

namespace SimpleAsFuck\LaravelLock\Factory;

use Symfony\Component\Lock\BlockingStoreInterface;

abstract class StoreFactory
{
    abstract public function make(): BlockingStoreInterface;
}
