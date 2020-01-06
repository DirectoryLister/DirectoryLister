<?php

namespace Tests;

use DI\Container;
use PHLAK\Config\Config;
use PHPUnit\Framework\TestCase as PHPUnitTestCase;

class TestCase extends PHPUnitTestCase
{
    /** @var Container The test container */
    protected $container;

    /** @var Config The test config */
    protected $config;

    /** @var string Path to test files directory */
    protected $testFilesPath = __DIR__ . '/_files';

    /**
     * This method is called before each test.
     *
     * @return void
     */
    public function setUp(): void
    {
        $this->container = new Container();
        $this->container->set('base_path', $this->testFilesPath);

        $this->config = new Config([
            'app' => [
                'sort_order' => 'type',
                'reverse_sort' => false,
                'hidden_files' => [],
                'hide_app_files' => true,
                'hide_vcs_files' => false,
                'max_hash_size' => 1000000000,
            ],
            'view' => [
                'cache' => false
            ],
        ]);
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
