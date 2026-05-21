<?php

declare(strict_types=1);

use DI\Container;
use Slim\Factory\AppFactory;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;

require __DIR__ . '/../vendor/autoload.php';

// Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->safeLoad();

// Create Container
$container = new Container();

// Set up Twig
$container->set(Twig::class, function () {
    return Twig::create(__DIR__ . '/../templates', [
        'cache' => false, // Set to __DIR__ . '/../cache' in production
    ]);
});

// Create App with container
AppFactory::setContainer($container);
$app = AppFactory::create();

// Add Twig middleware
$app->add(TwigMiddleware::createFromContainer($app, Twig::class));

// Add error middleware
$app->addErrorMiddleware(true, true, true);

// Routes
require __DIR__ . '/../src/routes.php';

$app->run();
