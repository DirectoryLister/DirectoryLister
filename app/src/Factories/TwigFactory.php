<?php

namespace App\Factories;

use App\Config;
use App\ViewFunctions\ViewFunction;
use Invoker\CallableResolver;
use Slim\Views\Twig;
use Twig\Extension\CoreExtension;
use Twig\Loader\FilesystemLoader;
use Twig\TwigFunction;

class TwigFactory
{
    /** Create a new TwigFactory object. */
    public function __construct(
        private Config $config,
        private CallableResolver $callableResolver
    ) {}

    /** Initialize and return the Twig component. */
    public function __invoke(): Twig
    {
        $twig = new Twig(new FilesystemLoader(
            $this->config->get('views_path')
        ), ['cache' => $this->config->get('view_cache')]);

        /** @var CoreExtension $core */
        $core = $twig->getEnvironment()->getExtension(CoreExtension::class);

        $core->setDateFormat($this->config->get('date_format'), '%d days');
        $core->setTimezone($this->config->get('timezone'));

        foreach ($this->config->get('view_functions') as $function) {
            /** @var ViewFunction&callable $function */
            $function = $this->callableResolver->resolve($function);

            $twig->getEnvironment()->addFunction(
                new TwigFunction($function->name(), $function)
            );
        }

        return $twig;
    }
}
