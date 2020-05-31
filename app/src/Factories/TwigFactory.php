<?php

namespace App\Factories;

use DI\Container;
use Invoker\CallableResolver;
use Slim\Views\Twig;
use Twig\Extension\CoreExtension;
use Twig\Loader\FilesystemLoader;
use Twig\TwigFunction;

class TwigFactory
{
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
        ), ['cache' => $this->container->get('view_cache')]);

        $environment = $twig->getEnvironment();
        $core = $environment->getExtension(CoreExtension::class);

        $core->setDateFormat($this->container->get('date_format'), '%d days');
        $core->setTimezone($this->container->get('timezone'));

        foreach ($this->container->get('view_functions') as $function) {
            $function = $this->callableResolver->resolve($function);

            $environment->addFunction(
                new TwigFunction($function->name(), $function)
            );
        }

        return $twig;
    }
}
