<?php

declare(strict_types=1);

namespace SimpleAsFuck\LaravelLock\Service;

use Illuminate\Contracts\Config\Repository;
use SimpleAsFuck\LaravelLock\Model\Lock;
use SimpleAsFuck\Validator\Factory\Validator;
use Symfony\Component\Lock\LockFactory;
use Symfony\Component\Lock\LockInterface;

class LockManager
{
    private LockFactory $lockFactory;
    private Repository $config;

    public function __construct(LockFactory $lockFactory, Repository $config)
    {
        $this->lockFactory = $lockFactory;
        $this->config = $config;
    }

    /**
     * method will wait for unlocked key and always return successfully acquired lock
     */
    public function acquire(string $key): Lock
    {
        $lock = $this->createSymfonyLock($key);
        $lock->acquire(true);

        return new Lock($lock);
    }

    /**
     * method will try to acquire lock for key, if key is in current time locked return null
     */
    public function acquireNotBlocking(string $key): ?Lock
    {
        $lock = $this->createSymfonyLock($key);
        if (! $lock->acquire(false)) {
            return null;
        }

        return new Lock($lock);
    }

    private function createSymfonyLock(string $key): LockInterface
    {
        $appName = Validator::make($this->config->get('app.name'))->string()->notNull();
        return $this->lockFactory->createLock($appName.$key, null, true);
    }
}
