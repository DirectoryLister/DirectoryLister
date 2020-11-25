<?php

namespace Tests;

use App\TemporaryFile;

/** @covers \App\TemporaryFile */
class TemporaryFileTest extends TestCase
{
    public function test_it_can_create_a_temporary_file(): void
    {
        $tempFile = new TemporaryFile($this->filePath('app/cache'));

        $this->assertFileExists((string) $tempFile);
    }

    public function test_it_can_write_to_and_read_from_a_temporary_file(): void
    {
        $tempFile = new TemporaryFile($this->filePath('app/cache'));

        $this->assertFileIsReadable((string) $tempFile);
        $this->assertFileIsWritable((string) $tempFile);

        file_put_contents((string) $tempFile, 'Test file; please ignore');

        $this->assertEquals('Test file; please ignore', $tempFile->getContents());
    }

    public function test_it_removes_the_underlying_file_on_destruction(): void
    {
        $tempFile = new TemporaryFile($this->filePath('app/cache'));
        $filePath = (string) $tempFile;

        unset($tempFile);

        $this->assertFalse(is_file($filePath));
    }
}
