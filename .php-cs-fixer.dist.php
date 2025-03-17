<?php

require __DIR__ . '/app/vendor/autoload.php';

$finder = PhpCsFixer\Finder::create()->in([
    __DIR__ . DIRECTORY_SEPARATOR . 'app',
    __DIR__ . DIRECTORY_SEPARATOR . 'tests',
])->notPath(['cache', 'vendor']);

return PHLAK\CodingStandards\ConfigFactory::make($finder, [
    'declare_strict_types' => true,
])->setCacheFile(
    implode(DIRECTORY_SEPARATOR, [__DIR__, '.cache', 'php-cs-fixer.cache'])
)->setRiskyAllowed(true);
