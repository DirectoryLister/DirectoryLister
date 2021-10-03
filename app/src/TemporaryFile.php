<?php

namespace App;

class TemporaryFile
{
    /** @var string Path to the temporary file */
    protected $path;

    /** Create a new TemporaryFile object. */
    public function __construct(string $dir, string $prefix = '')
    {
        $this->path = (string) tempnam($dir, $prefix);
    }

    /** Destroy this TemporaryFile object. */
    public function __destruct()
    {
        unlink($this->path);
    }

    /** Get the path to the temporary file. */
    public function __toString(): string
    {
        return $this->path;
    }

    /** Get the raw contents of the file. */
    public function getContents(): string
    {
        return (string) file_get_contents($this->path);
    }
}
