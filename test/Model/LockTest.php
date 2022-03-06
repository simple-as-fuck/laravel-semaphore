<?php

declare(strict_types=1);

use Illuminate\Contracts\Config\Repository;
use PHPUnit\Framework\TestCase;
use SimpleAsFuck\LaravelLock\Service\LockManager;
use Symfony\Component\Lock\LockFactory;
use Symfony\Component\Lock\Store\SemaphoreStore;

final class LockTest extends TestCase
{
    private LockManager $lockManager;

    protected function setUp(): void
    {
        $lockFactory = new LockFactory(new SemaphoreStore());
        $config = $this->createMock(Repository::class);
        $config->method('get')->willReturn('test');

        $this->lockManager = new LockManager($lockFactory, $config);
    }

    public function testDuplicateRelease(): void
    {
        $oldLock = $this->lockManager->acquireNotBlocking('test');
        $oldLock->release();

        self::assertFalse($oldLock->acquired());

        $newLock = $this->lockManager->acquireNotBlocking('test');

        $oldLock->release();

        self::assertFalse($oldLock->acquired());
        self::assertTrue($newLock->acquired());

        $newLock->release();

        self::assertFalse($oldLock->acquired());
        self::assertFalse($newLock->acquired());
    }
}
