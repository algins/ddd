<?php

use App\PostController;
use App\Domain\Events\DomainEventPublisher;
use App\Domain\Repositories\PostRepository;
use App\Domain\Repositories\SessionPostRepository;
use App\Domain\Subscribers\PostContentWasChangedSubscriber;
use App\Domain\Subscribers\PostTitleWasChangedSubscriber;
use App\Domain\Subscribers\PostWasCreatedSubscriber;
use App\Infrastructure\Projections\Projector;
use App\Infrastructure\Projections\Session\PostContentWasChangedProjection;
use App\Infrastructure\Projections\Session\PostTitleWasChangedProjection;
use App\Infrastructure\Projections\Session\PostWasCreatedProjection;
use App\Infrastructure\Projections\Session\PostWasRecreatedProjection;
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
        new PostWasRecreatedProjection(),
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
    DomainEventPublisher::instance()->subscribe(new PostWasRecreatedSubscriber());
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
