<?php

declare(strict_types=1);

namespace Tests;

use App\Bootstrap\Builder;
use App\Config;
use DI\Container;
use Dotenv\Dotenv;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Contracts\Cache\CacheInterface;
use Yoast\PHPUnitPolyfills\TestCases\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    /** @var Container The test container */
    protected $container;

    /** @var Config Application configuration */
    protected $config;

    /** @var CacheInterface The test cache */
    protected $cache;

    /** @var string Path to test files directory */
    protected $testFilesPath = __DIR__ . '/_files';

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

        $this->config = new Config($this->container);
        $this->cache = new ArrayAdapter((int) $this->config->get('cache_lifetime'));
    }

    /** Get the file path to a test file. */
    protected function filePath(string $filePath): string
    {
        return sprintf('%s/%s', $this->testFilesPath, $filePath);
    }
}
