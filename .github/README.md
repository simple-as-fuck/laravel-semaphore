# Simple as fuck / Laravel lock

Laravel integration for [symfony/lock](https://symfony.com/doc/current/components/lock.html).

## Installation

```console
composer require simple-as-fuck/laravel-lock
```

## Configuration

Add into your .env_example and configure your environment on server.

```dotenv
LOCK_STORE=semaphore
```

Supported symfony lock store are only with native blocking lock, because it is fucking effective.

- `semaphore` [SemaphoreStore](https://symfony.com/doc/current/components/lock.html#semaphorestore)
recommended for simple production without application server replication (lock are stored in local ram)

- `flock` [FlockStore](https://symfony.com/doc/current/components/lock.html#id3)
recommended for local development, (lock are stored in local filesystem, so it should work everywhere)

- `pgsql` [PostgreSqlStore](https://symfony.com/doc/current/components/lock.html#postgresqlstore)
recommended for big production with application server replication
(lock are stored remotely by postgres database),
you can use special database for locks using setting laravel database connection name
`LOCK_PGSQL_STORE_CONNECTION=some_postgers_connection_name`, by default is used default database connection

## Usage

```php
/** @var \SimpleAsFuck\LaravelLock\Service\LockManager $lockManager */
$lockManager = app()->make(\SimpleAsFuck\LaravelLock\Service\LockManager::class);

$lock = $lockManager->acquire('some_lock_key');
try {
    //happy run some critical code synchronously
} finally {
    $lock->release();
}
```
