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
        $_SERVER['SCRIPT_NAME'] = '/index.php';

        $this->config = new Config([
            'app' => [
                'dark_mode' => false,
                'display_readmes' => true,
                'zip_downloads' => true,
                'google_analytics_id' => false,
                'sort_order' => 'type',
                'reverse_sort' => false,
                'hidden_files' => [],
                'hide_app_files' => true,
                'hide_vcs_files' => false,
                'date_format' => 'Y-m-d H:i:s',
                'max_hash_size' => 1000000000,
                'view_cache' => false,
            ]
        ]);

        $this->container = new Container();
        $this->container->set('base_path', $this->testFilesPath);
        $this->container->set(Config::class, $this->config);
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
