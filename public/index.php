<?php

use App\PostController;
use App\PostRepository;
use App\PostService;
use App\SessionPostRepository;
use DI\Container;
use Slim\Factory\AppFactory;
use Slim\Middleware\MethodOverrideMiddleware;
use Slim\Views\PhpRenderer;

require __DIR__ . '/../vendor/autoload.php';

$container = new Container();

$container->set('renderer', function (Container $container) {
    $renderer = new PhpRenderer(__DIR__ . '/../templates');
    $renderer->setLayout('layout.phtml');
    return $renderer;
});

$container->set(PostRepository::class, function () {
    return new SessionPostRepository();
});

$container->set(PostService::class, function (Container $container) {
    $postRepository = $container->get(PostRepository::class);
    return new PostService($postRepository);
});

$app = AppFactory::createFromContainer($container);
$app->add(MethodOverrideMiddleware::class);
$app->addErrorMiddleware(true, true, true);

$app->redirect('/', '/posts', 301);
$app->get('/posts', [PostController::class, 'index']);
$app->get('/posts/create', [PostController::class, 'create']);
$app->post('/posts', [PostController::class, 'store']);
$app->get('/posts/{id}/edit', [PostController::class, 'edit']);
$app->patch('/posts/{id}', [PostController::class, 'update']);
$app->delete('/posts/{id}', [PostController::class, 'destroy']);

$app->run();