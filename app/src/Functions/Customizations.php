<?php

declare(strict_types=1);

namespace App\Functions;

use DI\Attribute\Inject;
use Twig\Markup;

class Customizations extends ViewFunction
{
    public string $name = 'customizations';

    #[Inject('customizations_file')]
    private string $customizationsFile;

    /** Get the contents of the customizations file. */
    public function __invoke(): Markup
    {
        if (! is_file($this->customizationsFile)) {
            return new Markup('', 'UTF-8');
        }

        $scripts = trim((string) file_get_contents($this->customizationsFile));

        return new Markup($scripts, 'UTF-8');
    }
}
