<?php

require __DIR__ . '/../vendor/autoload.php';

use Slim\Factory\AppFactory;
use Slim\Views\PhpRenderer;
use DI\Container;

$container = new Container();
$container->set('renderer', function () {
    $renderer = new PhpRenderer(__DIR__ . '/../templates');
    $renderer->setLayout('layout.phtml');
    return $renderer;
});

$app = AppFactory::createFromContainer($container);
$app->addErrorMiddleware(true, true, true);

$app->get('/posts', 'App\PostController:index')->setName('index');
$app->get('/posts/create', 'App\PostController:create')->setName('create');
$app->post('/posts', 'App\PostController:store')->setName('store');
$app->get('/posts/{id}', 'App\PostController:show')->setName('show');
$app->get('/posts/{id}/edit', 'App\PostController:edit')->setName('edit');
$app->patch('/posts/{id}', 'App\PostController:update')->setName('update');
$app->delete('/posts/{id}', 'App\PostController:destroy')->setName('destroy');

$app->run();