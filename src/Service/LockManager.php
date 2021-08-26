<?php

declare(strict_types=1);

namespace SimpleAsFuck\LaravelLock\Service;

use SimpleAsFuck\LaravelLock\Model\Lock;
use Symfony\Component\Lock\LockFactory;

class LockManager
{
    private LockFactory $lockFactory;

    public function __construct(LockFactory $lockFactory)
    {
        $this->lockFactory = $lockFactory;
    }

    /**
     * method will wait for unlocked key and always return successfully acquired lock
     */
    public function acquire(string $key): Lock
    {
        $lock = $this->lockFactory->createLock($key, null, true);
        $lock->acquire(true);

        return new Lock($lock);
    }

    /**
     * method will try to acquire lock for key, if key is in current time locked return null
     */
    public function acquireNotBlocking(string $key): ?Lock
    {
        $lock = $this->lockFactory->createLock($key, null, true);
        if (! $lock->acquire(false)) {
            return null;
        }

        return new Lock($lock);
    }
}
