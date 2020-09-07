<?php

namespace App\Factories;

use App\Config;
use Invoker\CallableResolver;
use Slim\Views\Twig;
use Twig\Extension\CoreExtension;
use Twig\Loader\FilesystemLoader;
use Twig\TwigFunction;

class TwigFactory
{
    /** @var Config The application configuration */
    protected $config;

    /** @var CallableResolver The callable resolver */
    protected $callableResolver;

    /** Create a new TwigFactory object. */
    public function __construct(
        Config $config,
        CallableResolver $callableResolver
    ) {
        $this->config = $config;
        $this->callableResolver = $callableResolver;
    }

    /** Initialize and return the Twig component. */
    public function __invoke(): Twig
    {
        $twig = new Twig(new FilesystemLoader(
            $this->config->get('views_path')
        ), ['cache' => $this->config->get('view_cache')]);

        $environment = $twig->getEnvironment();
        $core = $environment->getExtension(CoreExtension::class);

        $core->setDateFormat($this->config->get('date_format'), '%d days');
        $core->setTimezone($this->config->get('timezone'));

        foreach ($this->config->get('view_functions') as $function) {
            $function = $this->callableResolver->resolve($function);

            $environment->addFunction(
                new TwigFunction($function->name(), $function)
            );
        }

        return $twig;
    }
}
