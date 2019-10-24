<?php

namespace PHLAK\DirectoryLister;

use DirectoryIterator;
use FilterIterator;

class DirectoryLister extends FilterIterator
{
    public function __construct(string $path)
    {
        parent::__construct(
            new DirectoryIterator($path)
        );
    }

    public function accept()
    {
        if ($this->getInnerIterator()->current()->isDot()) {
            return false;
        }

        return true;
    }
}
