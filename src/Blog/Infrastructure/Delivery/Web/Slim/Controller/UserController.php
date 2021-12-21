<?php

namespace App\Blog\Infrastructure\Delivery\Web\Slim\Controller;

use App\Blog\Application\User\MakeUser\MakeUserRequest;
use App\Blog\Application\User\MakeUser\MakeUserService;
use App\Blog\Application\User\RemoveUser\RemoveUserRequest;
use App\Blog\Application\User\RemoveUser\RemoveUserService;
use App\Blog\Application\User\UpdateUser\UpdateUserRequest;
use App\Blog\Application\User\UpdateUser\UpdateUserService;
use App\Blog\Application\User\ViewUser\ViewUserRequest;
use App\Blog\Application\User\ViewUser\ViewUserResponse;
use App\Blog\Application\User\ViewUser\ViewUserService;
use App\Blog\Application\User\ViewUsers\ViewUsersResponse;
use App\Blog\Application\User\ViewUsers\ViewUsersService;
use App\Blog\Domain\Model\User\UserDoesNotExistException;
use App\Blog\Domain\Model\User\UserFactory;
use App\Blog\Domain\Model\User\UserRepository;
use InvalidArgumentException;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\PhpRenderer;

class UserController
{
    private PhpRenderer $renderer;
    private UserFactory $userFactory;
    private UserRepository $userRepository;

    public function __construct(
        ContainerInterface $container,
        UserFactory $userFactory,
        UserRepository $userRepository
    ) {
        $this->renderer = $container->get('renderer');
        $this->userFactory = $userFactory;
        $this->userRepository = $userRepository;
    }

    public function index(Request $request, Response $response): Response
    {
        $viewUsersService = new ViewUsersService($this->userRepository);
        $users = $viewUsersService->execute();

        $params = [
            'users' => $users,
        ];

        return $this->renderer->render($response, 'User/index.phtml', $params);
    }

    public function create(Request $request, Response $response): Response
    {
        $params = [
            'user' => null,
            'errors' => [],
        ];

        return $this->renderer->render($response, 'User/create.phtml', $params);
    }

    public function store(Request $request, Response $response): Response
    {
        $userData = $request->getParsedBodyParam('user');

        $makeUserService = new MakeUserService($this->userFactory, $this->userRepository);
        $makeUserRequest = new MakeUserRequest($userData['first_name'], $userData['last_name']);

        try {
            $makeUserService->execute($makeUserRequest);
        } catch (InvalidArgumentException $e) {
            $params = [
                'old' => $makeUserRequest,
                'errors' => [$e->getMessage()],
            ];

            return $this->renderer->render($response->withStatus(422), 'User/create.phtml', $params);
        }

        return $response->withRedirect('/users');
    }

    public function edit(Request $request, Response $response, array $args): Response
    {
        $id = $args['id'];

        $viewUserService = new ViewUserService($this->userRepository);
        $viewUserRequest = new ViewUserRequest($id);

        try {
            $user = $viewUserService->execute($viewUserRequest);
        } catch (UserDoesNotExistException $e) {
            return $response->write('User not found')->withStatus(404);
        }

        $params = [
            'user' => $user,
            'errors' => [],
        ];

        return $this->renderer->render($response, 'User/edit.phtml', $params);
    }

    public function update(Request $request, Response $response, array $args): Response
    {
        $id = $args['id'];
        $userData = $request->getParsedBodyParam('user');

        $updateUserService = new UpdateUserService($this->userRepository);
        $updateUserRequest = new UpdateUserRequest($id, $userData['first_name'], $userData['last_name']);

        try {
            $user = $updateUserService->execute($updateUserRequest);
        } catch (UserDoesNotExistException $e) {
            return $response->write('User not found')->withStatus(404);
        } catch (InvalidArgumentException $e) {
            $params = [
                'old' => $updateUserRequest,
                'errors' => [$e->getMessage()],
            ];

            return $this->renderer->render($response->withStatus(422), 'User/edit.phtml', $params);
        }

        return $response->withRedirect('/users');
    }

    public function destroy(Request $request, Response $response, array $args): Response
    {
        $id = $args['id'];

        $removeUserService = new RemoveUserService($this->userRepository);
        $removeUserRequest = new RemoveUserRequest($id);

        try {
            $removeUserService->execute($removeUserRequest);
        } catch (UserDoesNotExistException $e) {
            return $response->write('User not found')->withStatus(404);
        }

        return $response->withRedirect('/users');
    }
}
