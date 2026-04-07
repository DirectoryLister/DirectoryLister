<?php

declare(strict_types=1);

namespace App\Factories;

use DI\Attribute\Inject;
use DI\Bridge\Slim\Bridge;
use DI\Container;
use Slim\App;

class AppFactory
{
    /** @var array<class-string> */
    #[Inject('managers')]
    private array $managers;

    public function __construct(
        private Container $container,
    ) {}

    /** @return App<Container> */
    public function __invoke(): App
    {
        $app = Bridge::create($this->container);

        foreach ((array) $this->managers as $manager) {
            /** @var callable $manager */
            $this->container->call($manager);
        }

        return $app;
    }
}
