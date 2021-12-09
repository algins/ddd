<?php

use App\Blog\Domain\Event\PersistDomainEventSubscriber;
use App\Blog\Domain\Model\Post\PostRepository;
use App\Blog\Infrastructure\Persistence\Session\SessionEventStore;
use App\Blog\Infrastructure\Persistence\Session\SessionPostRepository;
use App\Blog\Infrastructure\Persistence\Session\Projections\PostContentWasChangedProjection;
use App\Blog\Infrastructure\Persistence\Session\Projections\PostTitleWasChangedProjection;
use App\Blog\Infrastructure\Persistence\Session\Projections\PostWasCreatedProjection;
use App\Blog\UI\API\Controllers\EventController;
use App\Blog\UI\WEB\Controllers\PostController;
use App\Shared\Domain\DomainEventPublisher;
use App\Shared\Domain\Model\Event\EventStore;
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

$container->set(EventStore::class, function () {
    return new SessionEventStore();
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

$container->set(PersistDomainEventSubscriber::class, function (Container $container) {
    $eventStore = $container->get(EventStore::class);
    return new PersistDomainEventSubscriber($eventStore);
});

DomainEventPublisher::instance()->subscribe($container->get(PersistDomainEventSubscriber::class));

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

$app->get('/events', [EventController::class, 'index']);

$app->run();
