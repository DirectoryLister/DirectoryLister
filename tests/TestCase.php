<?php

namespace Tests;

use DI\Container;
use PHLAK\Config\Config;
use PHPUnit\Framework\TestCase as PHPUnitTestCase;
use Symfony\Component\Translation\Loader\ArrayLoader;
use Symfony\Component\Translation\Translator;
use Symfony\Contracts\Translation\TranslatorInterface;

class TestCase extends PHPUnitTestCase
{
    /** @var Container The test container */
    protected $container;

    /** @var Config The test config */
    protected $config;

    /** @var TranslatorInterface Test translator */
    protected $translator;

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
                'hide_vcs_files' => false,
                'date_format' => 'Y-m-d H:i:s',
                'max_hash_size' => 1000000000,
                'view_cache' => false,
                'http_expires' => [
                    'application/zip' => '+1 hour',
                    'text/json' => '+1 hour',
                ],
            ]
        ]);

        $this->translator = new Translator('en');
        $this->translator->addLoader('array', new ArrayLoader);
        $this->translator->addResource('array', [
            'home' => 'Home',
            'download' => 'Download this Directory',
            'search' => 'Search',
            'file' => [
                'name' => 'File Name',
                'size' => 'Size',
                'date' => 'Date',
                'info' => 'File Info',
                'powered_by' => 'Powered by',
                'scroll_to_top' => 'Scroll to Top',
            ],
            'error' => [
                'directory_not_found' => 'Directory does not exist',
                'file_not_found' => 'File not found',
                'file_size_exceeded' => 'File size too large',
                'no_results_found' => 'No results found',
                'unexpected' => 'An unexpected error occurred',
            ],
            'enable_debugging' => 'Enable debugging for additional information',
        ], 'en');

        $this->container = new Container();
        $this->container->set('base_path', $this->testFilesPath);
        $this->container->set(Config::class, $this->config);
        $this->container->set(TranslatorInterface::class, $this->translator);
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
