<?php

declare(strict_types=1);

namespace SimpleAsFuck\LaravelLock\Model;

final class LockCollection
{
    /** @var array<string, \WeakReference<Lock>> */
    private array $acquiredLocks;

    public function __construct()
    {
        $this->acquiredLocks = [];
    }

    public function put(string $key, Lock $acquiredLock): void
    {
        $this->acquiredLocks[$key] = \WeakReference::create($acquiredLock);
    }

    public function hasAcquired(string $key): bool
    {
        if (array_key_exists($key, $this->acquiredLocks)) {
            $lock = $this->acquiredLocks[$key]->get();
            if ($lock !== null && $lock->acquired()) {
                return true;
            } else {
                unset($this->acquiredLocks[$key]);
            }
        }

        return false;
    }

    public function remove(Lock $lock): void
    {
        foreach ($this->acquiredLocks as $key => $acquiredLock) {
            $acquiredLock = $acquiredLock->get();
            if ($acquiredLock !== null && $acquiredLock !== $lock) {
                continue;
            }

            unset($this->acquiredLocks[$key]);
        }
    }
}
