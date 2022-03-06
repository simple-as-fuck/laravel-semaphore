<?php

declare(strict_types=1);

namespace SimpleAsFuck\LaravelLock\Model;

use Symfony\Component\Lock\LockInterface;

class Lock
{
    private LockInterface $lock;
    private ?LockCollection $lockCollection;

    public function __construct(LockInterface $lock, ?LockCollection $lockCollection = null)
    {
        $this->lock = $lock;
        $this->lockCollection = $lockCollection;
    }

    public function __destruct()
    {
        $this->release();
    }

    public function release(): void
    {
        if ($this->lock->isAcquired()) {
            $this->lock->release();
        }

        if ($this->lockCollection) {
            $this->lockCollection->remove($this);
        }
    }

    public function acquired(): bool
    {
        return $this->lock->isAcquired();
    }
}
