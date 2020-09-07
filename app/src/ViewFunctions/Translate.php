<?php

namespace App\ViewFunctions;

use Symfony\Contracts\Translation\TranslatorInterface;

class Translate extends ViewFunction
{
    /** @var string The function name */
    protected $name = 'translate';

    /** @var TranslatorInterface The application translator */
    protected $translator;

    /** Create a new Translate object. */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /** Retrieve a translated string by ID. */
    public function __invoke(string $id): string
    {
        return $this->translator->trans($id);
    }
}
