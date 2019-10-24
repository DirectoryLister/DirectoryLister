<?php

use DI\Container;
use DI\Bridge\Slim\Bridge;
use PHLAK\DirectoryLister\Controllers;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;

require __DIR__ . '/vendor/autoload.php';

$container = new Container();
$container->set(Twig::class, new Twig('themes/default', ['cache' => 'cache']));

$app = Bridge::create($container);
$app->add(TwigMiddleware::createFromContainer($app, Twig::class));

$app->get('/[{path:.*}]', Controllers\DirectoryController::class);

$app->run();
