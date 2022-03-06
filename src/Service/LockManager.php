<?php

declare(strict_types=1);

namespace SimpleAsFuck\LaravelLock\Service;

use Illuminate\Contracts\Config\Repository;
use SimpleAsFuck\LaravelLock\Model\FakeLock;
use SimpleAsFuck\LaravelLock\Model\Lock;
use SimpleAsFuck\LaravelLock\Model\LockCollection;
use SimpleAsFuck\Validator\Factory\Validator;
use Symfony\Component\Lock\LockFactory;
use Symfony\Component\Lock\LockInterface;

class LockManager
{
    private LockFactory $lockFactory;
    private Repository $config;

    private static LockCollection $lockCollection;

    public function __construct(LockFactory $lockFactory, Repository $config)
    {
        $this->lockFactory = $lockFactory;
        $this->config = $config;
        self::$lockCollection ??= new LockCollection();
    }

    /**
     * method will wait for unlocked key by another process and always return successfully acquired lock
     */
    public function acquire(string $key): Lock
    {
        $lock = $this->createSymfonyLock($key);
        if (self::$lockCollection->hasAcquired($key)) {
            return new FakeLock($lock);
        }

        $lock->acquire(true);

        $lock = new Lock($lock, self::$lockCollection);
        self::$lockCollection->put($key, $lock);
        return $lock;
    }

    /**
     * method will try to acquire lock for key, if key is in current time locked by another process return null
     */
    public function acquireNotBlocking(string $key): ?Lock
    {
        $lock = $this->createSymfonyLock($key);
        if (self::$lockCollection->hasAcquired($key)) {
            return new FakeLock($lock);
        }

        if (! $lock->acquire(false)) {
            return null;
        }

        $lock = new Lock($lock, self::$lockCollection);
        self::$lockCollection->put($key, $lock);
        return $lock;
    }

    private function createSymfonyLock(string $key): LockInterface
    {
        $appName = Validator::make($this->config->get('app.name'))->string()->notNull();
        return $this->lockFactory->createLock($appName.$key, null, true);
    }
}
