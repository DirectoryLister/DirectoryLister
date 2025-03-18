<?php

declare(strict_types=1);

namespace App\ViewFunctions;

use Symfony\Contracts\Translation\TranslatorInterface;

class Translate extends ViewFunction
{
    protected string $name = 'translate';

    public function __construct(
        private TranslatorInterface $translator
    ) {}

    /** Retrieve a translated string by ID. */
    public function __invoke(string $id): string
    {
        return $this->translator->trans($id);
    }
}
