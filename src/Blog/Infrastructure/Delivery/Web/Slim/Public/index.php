<?php

use App\Blog\Application\Post\MakePost\MakePostService;
use App\Blog\Application\Post\RemovePost\RemovePostService;
use App\Blog\Application\Post\UpdatePost\UpdatePostService;
use App\Blog\Application\Post\ViewPost\ViewPostService;
use App\Blog\Application\Post\ViewPosts\ViewPostsService;
use App\Blog\Application\User\MakeUser\MakeUserService;
use App\Blog\Application\User\RemoveUser\RemoveUserService;
use App\Blog\Application\User\UpdateUser\UpdateUserService;
use App\Blog\Application\User\ViewUser\ViewUserService;
use App\Blog\Application\User\ViewUsers\ViewUsersService;
use App\Blog\Domain\Event\PersistDomainEventSubscriber;
use App\Blog\Domain\Model\Post\PostFactory;
use App\Blog\Domain\Model\Post\PostRepository;
use App\Blog\Domain\Model\User\UserFactory;
use App\Blog\Domain\Model\User\UserRepository;
use App\Blog\Infrastructure\Delivery\Web\Slim\Controller\PostController;
use App\Blog\Infrastructure\Delivery\Web\Slim\Controller\UserController;
use App\Blog\Infrastructure\Domain\Model\Post\Session\SessionPostFactory;
use App\Blog\Infrastructure\Domain\Model\Post\Session\SessionPostRepository;
use App\Blog\Infrastructure\Domain\Model\User\Session\SessionUserFactory;
use App\Blog\Infrastructure\Domain\Model\User\Session\SessionUserRepository;
use App\Shared\Domain\Model\Event\DomainEventPublisher;
use App\Shared\Domain\Model\Event\EventStore;
use App\Shared\Infrastructure\Domain\Model\Event\Session\SessionEventStore;
use DI\Container;
use Slim\Factory\AppFactory;
use Slim\Middleware\MethodOverrideMiddleware;
use Slim\Views\PhpRenderer;

require __DIR__ . '/../../../../../../../vendor/autoload.php';

$container = new Container();

$container->set('renderer', function (Container $container) {
    $renderer = new PhpRenderer(__DIR__ . '/../../../Phtml/Views');
    $renderer->setLayout('layout.phtml');
    return $renderer;
});

$container->set(UserFactory::class, function () {
    return new SessionUserFactory();
});

$container->set(UserRepository::class, function () {
    return new SessionUserRepository();
});

$container->set(UserController::class, function (Container $container) {
    $userFactory = $container->get(UserFactory::class);
    $userRepository = $container->get(UserRepository::class);

    return new UserController(
        $container,
        new MakeUserService($userFactory, $userRepository),
        new RemoveUserService($userRepository),
        new UpdateUserService($userRepository),
        new ViewUserService($userRepository),
        new ViewUsersService($userRepository)
    );
});

$container->set(PostFactory::class, function () {
    return new SessionPostFactory();
});

$container->set(PostRepository::class, function () {
    return new SessionPostRepository();
});

$container->set(PostController::class, function (Container $container) {
    $postFactory = $container->get(PostFactory::class);
    $postRepository = $container->get(PostRepository::class);
    $userRepository = $container->get(UserRepository::class);

    return new PostController(
        $container,
        new MakePostService($postRepository, $userRepository),
        new RemovePostService($postRepository),
        new UpdatePostService($postRepository),
        new ViewPostService($postRepository, $userRepository),
        new ViewPostsService($postRepository, $userRepository),
        new ViewUsersService($userRepository)
    );
});

$container->set(EventStore::class, function () {
    return new SessionEventStore();
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

$app->get('/users', [UserController::class, 'index']);
$app->get('/users/create', [UserController::class, 'create']);
$app->post('/users', [UserController::class, 'store']);
$app->get('/users/{id}/edit', [UserController::class, 'edit']);
$app->patch('/users/{id}', [UserController::class, 'update']);
$app->delete('/users/{id}', [UserController::class, 'destroy']);

$app->run();
