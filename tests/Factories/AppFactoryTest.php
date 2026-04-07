<?php

declare(strict_types=1);

namespace Tests\Factories;

use App\Factories\AppFactory;
use DI\Container;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Slim\App;
use Tests\TestCase;

#[CoversClass(AppFactory::class)]
class AppFactoryTest extends TestCase
{
    #[Test]
    public function it_returns_the_application_and_registers_the_commands(): void
    {
        /** @var App<Container> $app */
        $app = $this->container->call(AppFactory::class);

        $this->assertInstanceOf(App::class, $app);
        $this->assertInstanceOf(App::class, $this->container->get(App::class));
    }
}
