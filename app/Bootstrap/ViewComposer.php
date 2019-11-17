<?php

namespace App\Bootstrap;

use PHLAK\Config\Config;
use Slim\Views\Twig;
use Twig\Extension\CoreExtension;
use Twig\TwigFunction;

class ViewComposer
{
    /** @var Config Application config */
    protected $config;

    /** @var Twig Twig instance */
    protected $twig;

    /**
     * Create a new ViewComposer object.
     *
     * @param \PHLAK\Config\Config $config
     */
    public function __construct(Config $config, Twig $twig)
    {
        $this->config = $config;
        $this->twig = $twig;
    }

    /**
     * Set up the Twig component.
     *
     * @return void
     */
    public function __invoke(): void
    {
        $this->twig->getEnvironment()->getExtension(CoreExtension::class)->setDateFormat(
            $this->config->get('date_format', 'Y-m-d H:i:s'), '%d days'
        );

        $this->twig->getEnvironment()->addFunction(
            new TwigFunction('asset', function ($path) {
                return "/app/themes/{$this->config->get('theme', 'defualt')}/{$path}";
            })
        );

        $this->twig->getEnvironment()->addFunction(
            new TwigFunction('sizeForHumans', function ($bytes) {
                $sizes = ['B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
                $factor = floor((strlen($bytes) - 1) / 3);

                return sprintf('%.2f', $bytes / pow(1024, $factor)) . $sizes[$factor];
            })
        );

        $this->registerThemeFunctions();
    }

    /**
     * Register theme Twig functions.
     *
     * @return void
     */
    public function registerThemeFunctions(): void
    {
        $themeConfigPath = "app/themes/{$this->config->get('theme')}/config.php";

        if (file_exists($themeConfigPath)) {
            $themeConfig = include $themeConfigPath;
        }

        foreach ($themeConfig['functions'] ?? [] as $function) {
            $this->twig->getEnvironment()->addFunction($function);
        }
    }
}
