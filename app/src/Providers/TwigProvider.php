<?php

namespace App\Providers;

use App\ViewFunctions;
use DI\Container;
use Invoker\CallableResolver;
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
        ViewFunctions\Breadcrumbs::class,
        ViewFunctions\Config::class,
        ViewFunctions\Icon::class,
        ViewFunctions\Markdown::class,
        ViewFunctions\ParentDir::class,
        ViewFunctions\SizeForHumans::class,
        ViewFunctions\Translate::class,
        ViewFunctions\Url::class,
    ];

    /** @var Container The application container */
    protected $container;

    /** @var Config Application config */
    protected $config;

    /** @var CallableResolver The callable resolver */
    protected $callableResolver;

    /**
     * Create a new ViewProvider object.
     *
     * @param \DI\Container             $container
     * @param \PHLAK\Config\Config      $config
     * @param \Invoker\CallableResolver $callableResolver
     */
    public function __construct(
        Container $container,
        Config $config,
        CallableResolver $callableResolver
    ) {
        $this->container = $container;
        $this->config = $config;
        $this->callableResolver = $callableResolver;
    }

    /**
     * Initialize and register the Twig component.
     *
     * @return void
     */
    public function __invoke(): void
    {
        $twig = new Twig(new FilesystemLoader('app/views'));

        $twig->getEnvironment()->setCache(
            $this->config->get('app.view_cache', 'app/cache/views')
        );

        $twig->getEnvironment()->getExtension(CoreExtension::class)->setDateFormat(
            $this->config->get('app.date_format', 'Y-m-d H:i:s'), '%d days'
        );

        foreach (self::VIEW_FUNCTIONS as $function) {
            $function = $this->callableResolver->resolve($function);

            $twig->getEnvironment()->addFunction(
                new TwigFunction($function->name(), $function)
            );
        }

        $this->container->set(Twig::class, $twig);
    }
}
