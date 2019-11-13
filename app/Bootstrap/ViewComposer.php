<?php

namespace App\Bootstrap;

use PHLAK\Config\Config;
use Slim\Views\Twig;
use Twig\Extension\CoreExtension;
use Twig\TwigFunction;

class ViewComposer
{
    /**
     * Set up the Twig component.
     *
     * @return void
     */
    public function __invoke(Config $config, Twig $twig): void
    {
        $twig->getEnvironment()->getExtension(CoreExtension::class)->setDateFormat(
            $config->get('date_format', 'Y-m-d H:i:s'), '%d days'
        );

        $twig->getEnvironment()->addFunction(
            new TwigFunction('asset', function ($path) use ($config) {
                return "/app/themes/{$config->get('theme', 'defualt')}/{$path}";
            })
        );

        $twig->getEnvironment()->addFunction(
            new TwigFunction('icon', function ($file) use ($config) {
                $extension = pathinfo($file, PATHINFO_EXTENSION);

                return $config->get("icons.{$extension}", 'fa-file');
            })
        );

        $twig->getEnvironment()->addFunction(
            new TwigFunction('sizeForHumans', function ($bytes) {
                $sizes = ['B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
                $factor = floor((strlen($bytes) - 1) / 3);

                return sprintf('%.2f', $bytes / pow(1024, $factor)) . $sizes[$factor];
            })
        );
    }
}
