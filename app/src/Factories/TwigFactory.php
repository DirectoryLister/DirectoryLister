<?php

namespace App\Factories;

use App\ViewFunctions;
use DI\Container;
use Invoker\CallableResolver;
use Slim\Views\Twig;
use Twig\Extension\CoreExtension;
use Twig\Loader\FilesystemLoader;
use Twig\TwigFunction;

class TwigFactory
{
    /** @const Constant description */
    protected const VIEW_FUNCTIONS = [
        ViewFunctions\Asset::class,
        ViewFunctions\Breadcrumbs::class,
        ViewFunctions\Config::class,
        ViewFunctions\FileUrl::class,
        ViewFunctions\Icon::class,
        ViewFunctions\Markdown::class,
        ViewFunctions\ParentUrl::class,
        ViewFunctions\SizeForHumans::class,
        ViewFunctions\Translate::class,
        ViewFunctions\Url::class,
        ViewFunctions\ZipUrl::class,
    ];

    /** @var Container The application container */
    protected $container;

    /** @var CallableResolver The callable resolver */
    protected $callableResolver;

    /**
     * Create a new TwigFactory object.
     *
     * @param \DI\Container             $container
     * @param \Invoker\CallableResolver $callableResolver
     */
    public function __construct(
        Container $container,
        CallableResolver $callableResolver
    ) {
        $this->container = $container;
        $this->callableResolver = $callableResolver;
    }

    /**
     * Initialize and return the Twig component.
     *
     * @return \Slim\Views\Twig
     */
    public function __invoke(): Twig
    {
        $twig = new Twig(new FilesystemLoader(
            $this->container->get('views_path')
        ));

        $twig->getEnvironment()->setCache(
            $this->container->get('view_cache')
        );

        $twig->getEnvironment()->getExtension(CoreExtension::class)->setDateFormat(
            $this->container->get('date_format'), '%d days'
        );

        $twig->getEnvironment()->getExtension(CoreExtension::class)->setTimezone(
            $this->container->get('timezone')
        );

        foreach (self::VIEW_FUNCTIONS as $function) {
            $function = $this->callableResolver->resolve($function);

            $twig->getEnvironment()->addFunction(
                new TwigFunction($function->name(), $function)
            );
        }

        return $twig;
    }
}
