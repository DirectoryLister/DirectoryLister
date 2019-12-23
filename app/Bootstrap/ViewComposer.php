<?php

namespace App\Bootstrap;

use DI\Container;
use PHLAK\Config\Config;
use Slim\Views\Twig;
use Symfony\Component\Finder\SplFileInfo;
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
        $twig = new Twig('app/resources/views');

        $twig->getEnvironment()->setCache(
            $this->config->get('view.cache', 'app/cache/views')
        );

        $twig->getEnvironment()->getExtension(CoreExtension::class)->setDateFormat(
            $this->config->get('app.date_format', 'Y-m-d H:i:s'), '%d days'
        );

        $this->registerFunctions($twig);

        $this->container->set(Twig::class, $twig);
    }

    /**
     * Register Twig functions.
     *
     * @param \Slim\Views\Twig $twig
     *
     * @return void
     */
    protected function registerFunctions(Twig $twig): void
    {
        $twig->getEnvironment()->addFunction(
            new TwigFunction('asset', function (string $path) use ($twig) {
                return "/app/dist/{$path}";
            })
        );

        $twig->getEnvironment()->addFunction(
            new TwigFunction('sizeForHumans', function (int $bytes) {
                $sizes = ['B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
                $factor = (int) floor((strlen((string) $bytes) - 1) / 3);

                return sprintf('%.2f', $bytes / pow(1024, $factor)) . $sizes[$factor];
            })
        );

        $twig->getEnvironment()->addFunction(
            new TwigFunction('icon', function (SplFileInfo $file) {
                $iconConfig = $this->config->split('icons');

                $icon = $file->isDir() ? 'fas fa-folder'
                    : $iconConfig->get($file->getExtension(), 'fas fa-file');

                return "<i class=\"{$icon} fa-fw fa-lg\"></i>";
            })
        );

        $twig->getEnvironment()->addFunction(
            new TwigFunction('config', function (string $key, $default = null) {
                $viewConfig = $this->config->split('view');

                return $viewConfig->get($key, $default);
            })
        );
    }
}
