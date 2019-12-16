<?php

namespace App\Bootstrap;

use DI\Container;
use PHLAK\Config\Config;
use Slim\Views\Twig;
use Twig\Extension\CoreExtension;
use Twig\TwigFunction;

class ViewComposer
{
    /** @var Container The application container */
    protected $container;

    /** @var Config Application config */
    protected $config;

    /**
     * Create a new ViewComposer object.
     *
     * @param \DI\Container        $container
     * @param \PHLAK\Config\Config $config
     */
    public function __construct(Container $container, Config $config)
    {
        $this->container = $container;
        $this->config = $config;
    }

    /**
     * Set up the Twig component.
     *
     * @return void
     */
    public function __invoke(): void
    {
        $twig = new Twig("app/themes/{$this->config->get('theme', 'default')}");

        $twig->getEnvironment()->setCache(
            $this->config->get('view_cache', 'app/cache/views')
        );

        $twig->getEnvironment()->getExtension(CoreExtension::class)->setDateFormat(
            $this->config->get('date_format', 'Y-m-d H:i:s'), '%d days'
        );

        $this->registerGlobalFunctions($twig);
        $this->registerThemeFunctions($twig);

        $this->container->set(Twig::class, $twig);
    }

    /**
     * Register global Twig functions.
     *
     * @param \Slim\Views\Twig $twig
     *
     * @return void
     */
    protected function registerGlobalFunctions(Twig $twig): void
    {
        $twig->getEnvironment()->addFunction(
            new TwigFunction('asset', function (string $path) use ($twig) {
                return "/{$twig->getLoader()->getPaths()[0]}/{$path}";
            })
        );

        $twig->getEnvironment()->addFunction(
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
     * @param \Slim\Views\Twig $twig
     *
     * @return void
     */
    protected function registerThemeFunctions(Twig $twig): void
    {
        $themeFunctionsFile = "{$twig->getLoader()->getPaths()[0]}/functions.php";

        if (file_exists($themeFunctionsFile)) {
            $themeConfig = include $themeFunctionsFile;
        }

        foreach ($themeConfig['functions'] ?? [] as $function) {
            $twig->getEnvironment()->addFunction($function);
        }
    }
}
