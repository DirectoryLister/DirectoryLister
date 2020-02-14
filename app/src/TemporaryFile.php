<?php

namespace App;

class TemporaryFile
{
    /** @var string Path to the temporary file */
    protected $path;

    /**
     * Create a new TemporaryFile object.
     *
     * @param string $dir
     * @param string $prefix
     */
    public function __construct(string $dir, string $prefix = '')
    {
        $this->path = tempnam($dir, $prefix);
    }

    /** Destroy this TemporaryFile object. */
    public function __destruct()
    {
        unlink($this->path);
    }

    /**
     * Get the path to the temporary file.
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->path;
    }

    /**
     * Get the raw contents of the file.
     *
     * @return string
     */
    public function getContents(): string
    {
        return file_get_contents($this->path);
    }
}
