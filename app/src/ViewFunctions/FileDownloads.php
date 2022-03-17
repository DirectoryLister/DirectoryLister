<?php

namespace App\ViewFunctions;

use App\Config;
use ErrorException;
use Symfony\Component\Finder\SplFileInfo;

function updateDownloads()
{
    $dir = getcwd() . '/app/.downloads/.';
    $filename = $_GET['download'];
    $download_url = $_SERVER['SERVER_NAME'] . ':' . $_SERVER['SERVER_PORT'] . '/' . $filename;
    $filename = str_replace('\\', '', $filename);
    $handle = fopen($dir . $filename, 'r+');
    $data = (int) fread($handle, filesize($dir . $filename));
    fclose($handle);
    $handle = fopen($dir . $filename, 'w');
    ++$data;
    fwrite($handle, $data);
    fclose($handle);
    header('Content-Disposition: attachment; filename=\"' . basename($download_url) . '\"');
    header('Content-Type: application/force-download');
    header('Content-Length: ' . filesize($download_url));
    header('Connection: close');
}

if (isset($_GET['download'])) {
    updateDownloads();
}

class FileDownloads extends ViewFunction
{
    /** @var string The function name */
    protected $name = 'file_downloads';

    /** @var string Dir name */
    private $dir;

    public function __construct(Config $config)
    {
        $this->config = $config;
        $this->downloads = 0;
        $this->dir = getcwd() . '/app/.downloads/.';
    }

    /** Get the modified time from a file object. */
    public function __invoke(SplFileInfo $file): string
    {
        $data = 0;
        if (! file_exists($this->dir)) {
            mkdir($this->dir);
        }
        $filename = $file->getPathname()[0] != '.' ? $file->getPathname() : substr($file->getPathname(), 1);
        $filename = str_replace('\\', '', $filename);

        try {
            $fileHandle = fopen($this->dir . $filename, 'r');
        } catch (ErrorException $exception) {
            if ($exception->getCode() === 2) {
                $fileHandle = fopen($this->dir . $filename, 'w');
                fwrite($fileHandle, '0');
                fclose($fileHandle);
                $fileHandle = fopen($this->dir . $filename, 'r');
            }
        }
        $data = fread($fileHandle, filesize($file->getRealPath()));
        fclose($fileHandle);

        return $data;
    }
}
