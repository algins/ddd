<?php

use App\PostController;
use Slim\Factory\AppFactory;
use Slim\Views\PhpRenderer;
use DI\Container;

require __DIR__ . '/../vendor/autoload.php';

$container = new Container();
$container->set('renderer', function () {
    return new PhpRenderer(__DIR__ . '/../templates');
});

$app = AppFactory::createFromContainer($container);
$app->addErrorMiddleware(true, true, true);

$app->get('/', PostController::class);

$app->run();