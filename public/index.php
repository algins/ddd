<?php

use App\Post\Domain\PostRepository;
use App\Post\Domain\Subscribers\PostContentWasChangedSubscriber;
use App\Post\Domain\Subscribers\PostTitleWasChangedSubscriber;
use App\Post\Domain\Subscribers\PostWasCreatedSubscriber;
use App\Post\Infrastructure\Persistence\Session\SessionPostRepository;
use App\Post\Infrastructure\Persistence\Session\Projections\PostContentWasChangedProjection;
use App\Post\Infrastructure\Persistence\Session\Projections\PostTitleWasChangedProjection;
use App\Post\Infrastructure\Persistence\Session\Projections\PostWasCreatedProjection;
use App\Post\UI\Controllers\PostController;
use App\Shared\Domain\DomainEventPublisher;
use App\Shared\Infrastructure\Persistence\Projections\Projector;
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
    $projector = new Projector();
    $projector->register([
        new PostContentWasChangedProjection(),
        new PostTitleWasChangedProjection(),
        new PostWasCreatedProjection(),
    ]);
    return new SessionPostRepository($projector);
});

$container->set(PostController::class, function (Container $container) {
    $postRepository = $container->get(PostRepository::class);
    return new PostController($container, $postRepository);
});

$container->set(DomainEventPublisher::class, function () {
    DomainEventPublisher::instance()->subscribe(new PostContentWasChangedSubscriber());
    DomainEventPublisher::instance()->subscribe(new PostTitleWasChangedSubscriber());
    DomainEventPublisher::instance()->subscribe(new PostWasCreatedSubscriber());
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
