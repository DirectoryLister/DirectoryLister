<?php

namespace Tests;

use DI\Container;
use DI\ContainerBuilder;
use PHPUnit\Framework\TestCase as PHPUnitTestCase;

class TestCase extends PHPUnitTestCase
{
    /** @var Container The test container */
    protected $container;

    /** @var string Path to test files directory */
    protected $testFilesPath = __DIR__ . '/_files';

    /**
     * This method is called before each test.
     *
     * @return void
     */
    public function setUp(): void
    {
        $_SERVER['SCRIPT_NAME'] = '/index.php';

        $this->container = (new ContainerBuilder)->addDefinitions(
            dirname(__DIR__) . '/app/config/app.php',
            dirname(__DIR__) . '/app/definitions.php',
        )->build();

        $this->container->set('base_path', $this->testFilesPath);
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
