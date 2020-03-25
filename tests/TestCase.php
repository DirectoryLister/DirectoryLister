<?php

namespace Tests;

use App\Bootstrap\AppManager;
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
            [
                'debug' => false,
                'language' => 'en',
                'dark_mode' => false,
                'display_readmes' => true,
                'zip_downloads' => true,
                'google_analytics_id' => false,
                'sort_order' => 'type',
                'reverse_sort' => false,
                'hidden_files' => [],
                'hide_app_files' => true,
                'hide_vcs_files' => true,
                'date_format' => 'Y-m-d H:i:s',
                'max_hash_size' => 1000000000,
                'view_cache' => false,
                'http_expires' => [
                    'application/zip' => '+1 hour',
                    'text/json' => '+1 hour',
                ],
            ],
            dirname(__DIR__) . '/app/definitions.php',
        )->build();

        $this->container->set('base_path', $this->testFilesPath);

        // $this->app = $this->container->call(AppManager::class);
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
