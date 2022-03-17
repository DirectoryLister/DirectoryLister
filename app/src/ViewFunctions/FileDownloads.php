<?php

namespace App\ViewFunctions;

use Symfony\Component\Finder\SplFileInfo;

function updateDownloads(): void
{
    $dir = getcwd() . '/app/.downloads/.';
    $filename = $_GET['download'];
    $download_url = $_SERVER['SERVER_NAME'] . ':' . $_SERVER['SERVER_PORT'] . DIRECTORY_SEPARATOR . $filename;
    $filename = str_replace(DIRECTORY_SEPARATOR, '', $filename);
    $handle = fopen($dir . $filename, 'r+');
    if ($handle == false) {
        return;
    }
    $data = (int) fread($handle, (int) filesize($dir . $filename));
    fclose($handle);
    $handle = fopen($dir . $filename, 'w');
    if ($handle == false) {
        return;
    }
    ++$data;
    fwrite($handle, strval($data));
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

    public function __construct()
    {
        $this->dir = getcwd() . '/app/.downloads/.';
    }

    /** Get the modified time from a file object. */
    public function __invoke(SplFileInfo $file): string
    {
        $data = 0;
        if (! file_exists($this->dir)) {
            mkdir($this->dir, 0777, true);
        }
        $filename = $file->getPathname()[0] != '.' ? $file->getPathname() : substr($file->getPathname(), 1);
        $filename = $this->dir . str_replace(DIRECTORY_SEPARATOR, '', $filename);
        if (! file_exists($filename)) {
            $fileHandle = fopen($filename, 'w');
            if ($fileHandle == false) {
                return '0';
            }
            fwrite($fileHandle, '0');
            fclose($fileHandle);

            return '0';
        }
        $fileHandle = fopen($filename, 'r');
        if ($fileHandle == false) {
            return '0';
        }
        $data = fread($fileHandle, (int) filesize($file->getRealPath()));
        fclose($fileHandle);

        return strval($data);
    }
}
