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
            $creationTime = $file->getMTime();
            $fileOption->setTime(new \DateTime("@$creationTime"));
            $zip->addFileFromPath($this->stripPath($file, $path), (string) $file->getRealPath(), $fileOption);
        }

        $zip->finish();
    }

    protected function augmentHeadersWithEstimatedSize(Response $response, string $path, Finder $files): Response
    {
        if (! $this->config->get('zip_compress')) {
            $totalSize = 0;
            $filesMeta = [];
            foreach ($files as $file) {
                $fileSize = $file->getSize();
                $totalSize += $fileSize;
                $filesMeta[] = [strlen($this->stripPath($file, $path)), $fileSize];
            }
            # If there is more than 4 GB or 2^16 files, it will be a ZIP64, changing the estimation method
            if ($totalSize >= 2^32 || count($filesMeta) >= 0xFFFF) {
                $estimate = $this->calculateZip64Size($filesMeta);
            } else {
                $estimate = $this->calculateZipSize($filesMeta);
            }
            
            $response = $response->withHeader('Content-Length', (string) $estimate);
        }

        return $response;
    }

    protected function calculateZipSize(Array $filesMeta): int
    {
        $estimate = 22;
        foreach ($filesMeta as $fileMeta) {
            $estimate += 76 + 2 * $fileMeta[0] + $fileMeta[1];
        }
        return $estimate;
    }

    protected function calculateZip64Size(Array $filesMeta): int
    {
        # Size of the CDR calculated by ZipStream is always 44 + 12 for signature and the size itself
        $estimate = 56;
        # Size of the CRD locator (always 20 according to the spec)
        $estimate += 20;
        foreach ($filesMeta as $fileMeta) {
            # This is not different from standard Zip
            $estimate += 76 + 2 * $fileMeta[0] + $fileMeta[1];
            # This is where it gets funky
            $zip64ExtraBlockSize = 0;
            if ($fileMeta[1] >= 2^32) {
                # If file size is more than 2^32, add it to the extra block
                $zip64ExtraBlockSize += 16; // 8 for size + 8 for compressed size
            }
    
            # Offset
            if ($estimate >= 2^32) {
                $zip64ExtraBlockSize += 8; // if offset is more than 2^32, then we add it to the extra block
            }
    
            if ($zip64ExtraBlockSize != 0) {
                $zip64ExtraBlockSize += 4; // 2 for header ID + 2 for the block size 
            }

            # If the filename or path is in UTF-8, then ZipStream will add the two remaining fields with special values
            if (mb_check_encoding($filesMeta[0], 'UTF-8')) {
                $zip64ExtraBlockSize += 4;
            }

            $estimate += $zip64ExtraBlockSize;
        }
        return $estimate;
    }

    /** Return the path to a file with the preceding root path stripped. */
    protected function stripPath(SplFileInfo $file, string $path): string
    {
        $pattern = sprintf(
            '/^%s%s?/', preg_quote($path, '/'), preg_quote(DIRECTORY_SEPARATOR, '/')
        );

        return (string) preg_replace($pattern, '', $file->getPathname());
    }

    /** Generate the file name for a path. */
    protected function generateFileName(string $path): string
    {
        $filename = Str::explode($path, DIRECTORY_SEPARATOR)->last();

        return $filename == '.' ? 'Home' : $filename;
    }
}
