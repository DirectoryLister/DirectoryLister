<?php

namespace App\Providers;

use App\ViewFunctions;
use DI\Container;
use PHLAK\Config\Config;
use Slim\Views\Twig;
use Twig\Extension\CoreExtension;
use Twig\Loader\FilesystemLoader;
use Twig\TwigFunction;

class TwigProvider
{
    /** @const Constant description */
    protected const VIEW_FUNCTIONS = [
        ViewFunctions\Asset::class,
        ViewFunctions\Config::class,
        ViewFunctions\Icon::class,
        ViewFunctions\SizeForHumans::class,
    ];

    /** @var Container The application container */
    protected $container;

    /** @var Config Application config */
    protected $config;

    /**
     * Create a new ViewProvider object.
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
     * Initialize and register the Twig component.
     *
     * @return void
     */
    public function __invoke(): void
    {
        $twig = new Twig(new FilesystemLoader('app/resources/views'));

        $twig->getEnvironment()->setCache(
            $this->config->get('view.cache', 'app/cache/views')
        );

        $twig->getEnvironment()->getExtension(CoreExtension::class)->setDateFormat(
            $this->config->get('app.date_format', 'Y-m-d H:i:s'), '%d days'
        );

        foreach (self::VIEW_FUNCTIONS as $function) {
            $function = new $function($this->config);

            $twig->getEnvironment()->addFunction(
                new TwigFunction($function->name(), $function)
            );
        }

        $this->container->set(Twig::class, $twig);
    }
}
