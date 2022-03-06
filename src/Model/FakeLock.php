<?php

declare(strict_types=1);

namespace SimpleAsFuck\LaravelLock\Model;

use Symfony\Component\Lock\LockInterface;

final class FakeLock extends Lock
{
    private bool $acquired;

    public function __construct(LockInterface $lock)
    {
        parent::__construct($lock);
        $this->acquired = true;
    }

    public function acquired(): bool
    {
        return $this->acquired;
    }

    public function release(): void
    {
        $this->acquired = false;
    }
}
