<?php

namespace App\Controllers;

use App\CallbackStream;
use App\Config;
use App\Support\Str;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Contracts\Translation\TranslatorInterface;
use ZipStream\Option\Archive;
use ZipStream\Option\File;
use ZipStream\Option\Method;
use ZipStream\ZipStream;

class ZipController
{
    /** Create a new ZipHandler object. */
    public function __construct(
        private Config $config,
        private Finder $finder,
        private TranslatorInterface $translator
    ) {}

    /** Invoke the ZipHandler. */
    public function __invoke(Request $request, Response $response): ResponseInterface
    {
        $path = $request->getQueryParams()['zip'];

        if (! $this->config->get('zip_downloads') || ! is_dir($path)) {
            return $response->withStatus(404, $this->translator->trans('error.file_not_found'));
        }

        $response = $response
            ->withHeader('Content-Type', 'application/zip')
            ->withHeader('Content-Disposition', sprintf(
                'attachment; filename="%s.zip"',
                $this->generateFileName($path)
            ))
            ->withHeader('X-Accel-Buffering', 'no');

        $files = $this->finder->in($path)->files();

        $response = $this->augmentHeadersWithEstimatedSize($response, $path, $files);

        return $response->withBody(new CallbackStream(function () use ($path, $files) {
            $this->createZip($path, $files);
        }));
    }

    /** Create a zip stream from a directory.
     *
     * @throws \ZipStream\Exception\FileNotFoundException
     * @throws \ZipStream\Exception\FileNotReadableException
     * @throws \ZipStream\Exception\OverflowException
     */
    protected function createZip(string $path, Finder $files): void
    {
        $compressionMethod = $this->config->get('zip_compress') ? Method::DEFLATE() : Method::STORE();

        $zipStreamOptions = new Archive();
        $zipStreamOptions->setLargeFileMethod($compressionMethod);
        $zipStreamOptions->setSendHttpHeaders(false);
        $zipStreamOptions->setFlushOutput(true);
        $zipStreamOptions->setEnableZip64(true);

        $zip = new ZipStream(null, $zipStreamOptions);

        foreach ($files as $file) {
            $fileOption = new File();
            $fileOption->setMethod($compressionMethod);
            $fileOption->setSize($file->getSize());

            try {
                $creationTime = (int) $file->getMTime();
                $fileOption->setTime(new \DateTime('@' . $creationTime));
            } catch (\Exception $e) {
                // We couldn't get the creation time, so we don't set it
            }
            $zip->addFileFromPath($this->stripPath($file, $path), (string) $file->getRealPath(), $fileOption);
        }

        $zip->finish();
    }

    protected function augmentHeadersWithEstimatedSize(Response $response, string $path, Finder $files): Response
    {
        if (! $this->config->get('zip_compress')) {
            $totalSize = 0;
            $hasUtf8 = false;
            $filesMeta = [];
            foreach ($files as $file) {
                $fileSize = $file->getSize();
                $totalSize += $fileSize;
                $fileName = $this->filterFilename($this->stripPath($file, $path));
                if (! mb_check_encoding(preg_replace('/^\\/+/', '', $fileName), 'ASCII')) {
                    $hasUtf8 = true;
                }
                $filesMeta[] = [$fileName, $fileSize];
            }
            // If there is more than 4 GB or 2^16 files, it will be a ZIP64, changing the estimation method
            if (
                $totalSize >= pow(2, 32) ||
                count($filesMeta) >= 0xFFFF ||
                $hasUtf8
            ) {
                $estimate = $this->calculateZip64Size($filesMeta);
            } else {
                $estimate = $this->calculateZipSize($filesMeta);
            }

            $response = $response->withHeader('Content-Length', (string) $estimate);
        }

        return $response;
    }

    protected function calculateZipSize(array $filesMeta): int
    {
        $estimate = 22;
        foreach ($filesMeta as $fileMeta) {
            $estimate += 76 + 2 * strlen($fileMeta[0]) + $fileMeta[1];
        }

        return $estimate;
    }

    protected function calculateZip64Size(array $filesMeta): int
    {
        $estimate = 0;
        foreach ($filesMeta as $fileMeta) {
            $beginning = $estimate;
            // This is similar from standard Zip
            // Adding header size and filename
            $header = 30 + strlen($fileMeta[0]);
            // With Zip64, the size of the header is variable, so we need to calculate it
            $header += $this->calculateZip64ExtraBlockSize($fileMeta, 0);
            // Adding file content to the size
            $content = $fileMeta[1];
            // Default footer size (data descriptor) including filename
            $footer = 46 + strlen($fileMeta[0]);
            // This block also gets added at the end of the file, but offsets are differents, so we need to calculate it again
            $footer += $this->calculateZip64ExtraBlockSize($fileMeta, $beginning);
            $estimate += $header + $content + $footer;
        }
        // Size of the CDR64 EOF calculated by ZipStream is always 44 + 12 for signature and the size itself
        $estimate += 56;
        // Size of the CDR64 locator
        $estimate += 20;
        // Size of the CDR EOF locator
        $estimate += 22;

        return $estimate;
    }

    protected function calculateZip64ExtraBlockSize(array $fileMeta, int $currentOffset): int
    {
        // This is where it gets funky
        $zip64ExtraBlockSize = 0;
        if ($fileMeta[1] >= pow(2, 32)) {
            // If file size is more than 2^32, add it to the extra block
            $zip64ExtraBlockSize += 16; // 8 for size + 8 for compressed size
        }

        // Offset
        if ($currentOffset >= pow(2, 32)) {
            $zip64ExtraBlockSize += 8; // if offset is more than 2^32, then we add it to the extra block
        }

        if ($zip64ExtraBlockSize != 0) {
            $zip64ExtraBlockSize += 4; // 2 for header ID + 2 for the block size
        }

        // If the filename or path does not fit into ASCII, then ZipStream will add the two remaining fields with special values
        if (! mb_check_encoding(preg_replace('/^\\/+/', '', $fileMeta[0]), 'ASCII')) {
            $zip64ExtraBlockSize += 4;
        }

        return $zip64ExtraBlockSize;
    }

    /** Return the path to a file with the preceding root path stripped. */
    protected function stripPath(SplFileInfo $file, string $path): string
    {
        $pattern = sprintf(
            '/^%s%s?/',
            preg_quote($path, '/'),
            preg_quote(DIRECTORY_SEPARATOR, '/')
        );

        return (string) preg_replace($pattern, '', $file->getPathname());
    }

    /** Generate the file name for a path. */
    protected function generateFileName(string $path): string
    {
        $filename = Str::explode($path, DIRECTORY_SEPARATOR)->last();

        return $filename == '.' ? 'Home' : $filename;
    }

    /** Filter a file name to remove invalid characters inside a Zip */
    protected function filterFilename(string $filename): string
    {
        // strip leading slashes from file name
        // (fixes bug in windows archive viewer)
        $filename = (string) preg_replace('/^\\/+/', '', $filename);

        return str_replace(['\\', ':', '*', '?', '"', '<', '>', '|'], '_', $filename);
    }
}
