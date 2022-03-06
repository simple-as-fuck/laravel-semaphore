<?php

declare(strict_types=1);

namespace SimpleAsFuck\LaravelLock\Model;

use Symfony\Component\Lock\LockInterface;

final class Lock
{
    private LockInterface $lock;

    public function __construct(LockInterface $lock)
    {
        $this->lock = $lock;
    }

    public function release(): void
    {
        if ($this->lock->isAcquired()) {
            $this->lock->release();
        }
    }

    public function acquired(): bool
    {
        return $this->lock->isAcquired();
    }
}
