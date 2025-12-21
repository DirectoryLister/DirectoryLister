<?php

declare(strict_types=1);

namespace Tests;

use App\Bootstrap\Builder;
use DI\Container;
use Dotenv\Dotenv;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Contracts\Cache\CacheInterface;
use Yoast\PHPUnitPolyfills\TestCases\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    protected Container $container;
    protected CacheInterface $cache;
    protected string $testFilesPath = __DIR__ . '/_files';

    /** This method is called before each test. */
    protected function setUp(): void
    {
        parent::setUp();

        putenv('COMPILE_CONTAINER=false');

        Dotenv::createUnsafeImmutable(__DIR__)->safeLoad();

        $this->container = Builder::createContainer(
            dirname(__DIR__) . '/app/config',
            dirname(__DIR__) . '/app/cache'
        );

        $this->container->set('base_path', $this->testFilesPath);
        $this->container->set('cache_path', $this->filePath('app/cache'));
        $this->container->set('cache_driver', 'array');

        $this->cache = $this->container->get(CacheInterface::class);
    }

    /** Get the file path to a test file. */
    protected function filePath(string $filePath): string
    {
        return sprintf('%s/%s', $this->testFilesPath, $filePath);
    }

    /**
     * @template TClass of object
     *
     * @param class-string<TClass> $className
     *
     * @return TClass&MockObject
     */
    protected function mock(string $className): mixed
    {
        $mock = $this->createMock($className);

        $this->container->set($className, $mock);

        return $mock;
    }
}
