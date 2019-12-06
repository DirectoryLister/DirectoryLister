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

    /** @var string Path to theme */
    protected $themePath;

    /**
     * Create a new ViewComposer object.
     *
     * @param \PHLAK\Config\Config $config
     * @param \Slim\Views\Twig     $twig
     */
    public function __construct(Config $config, Twig $twig)
    {
        $this->config = $config;
        $this->twig = $twig;
        $this->themePath = $twig->getLoader()->getPaths()[0];
    }

    /**
     * Set up the Twig component.
     *
     * @return void
     */
    public function __invoke(): void
    {
        $this->twig->getEnvironment()->setCache(
            $this->config->get('view_cache', 'app/cache/views')
        );

        $this->twig->getEnvironment()->getExtension(CoreExtension::class)->setDateFormat(
            $this->config->get('date_format', 'Y-m-d H:i:s'), '%d days'
        );

        $this->registerGlobalFunctions();
        $this->registerThemeFunctions();
    }

    /**
     * Register global Twig functions.
     *
     * @return void
     */
    public function registerGlobalFunctions(): void
    {
        $this->twig->getEnvironment()->addFunction(
            new TwigFunction('asset', function (string $path) {
                return "/{$this->themePath}/{$path}";
            })
        );

        $this->twig->getEnvironment()->addFunction(
            new TwigFunction('sizeForHumans', function (int $bytes) {
                $sizes = ['B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
                $factor = (int) floor((strlen((string) $bytes) - 1) / 3);

                return sprintf('%.2f', $bytes / pow(1024, $factor)) . $sizes[$factor];
            })
        );
    }

    /**
     * Register theme specific Twig functions.
     *
     * @return void
     */
    public function registerThemeFunctions(): void
    {
        $themeConfigPath = "{$this->themePath}/config.php";

        if (file_exists($themeConfigPath)) {
            $themeConfig = include $themeConfigPath;
        }

        foreach ($themeConfig['functions'] ?? [] as $function) {
            $this->twig->getEnvironment()->addFunction($function);
        }
    }
}
