<?php

namespace Tests;

use DI\Container;
use DI\ContainerBuilder;
use Dotenv\Dotenv;
use PHPUnit\Framework\TestCase as PHPUnitTestCase;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Contracts\Cache\CacheInterface;

class TestCase extends PHPUnitTestCase
{
    /** @var Container The test container */
    protected $container;

    /** @var CacheInterface The test cache */
    protected $cache;

    /** @var string Path to test files directory */
    protected $testFilesPath = __DIR__ . '/_files';

    /**
     * This method is called before each test.
     *
     * @return void
     */
    public function setUp(): void
    {
        Dotenv::createUnsafeImmutable(__DIR__)->safeLoad();

        $this->container = call_user_func_array(
            [new ContainerBuilder, 'addDefinitions'],
            glob(dirname(__DIR__) . '/app/config/*.php')
        )->build();

        $this->cache = new ArrayAdapter($this->container->get('cache_lifetime'));

        $this->container->set('base_path', $this->testFilesPath);
        $this->container->set('asset_path', $this->filePath('app/assets'));
        $this->container->set('cache_path', $this->filePath('app/cache'));
    }

    /**
     * Get the file path to a test file.
     *
     * @param string $filePath
     *
     * @return string
     */
    protected function filePath(string $filePath): string
    {
        return realpath($this->testFilesPath . '/' . $filePath);
    }
}
