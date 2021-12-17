<?php

namespace App\Blog\Infrastructure\Delivery\Web\Slim\Controller;

use App\Blog\Application\User\CreateUser\CreateUserRequest;
use App\Blog\Application\User\CreateUser\CreateUserService;
use App\Blog\Application\User\DeleteUser\DeleteUserRequest;
use App\Blog\Application\User\DeleteUser\DeleteUserService;
use App\Blog\Application\User\FindAllUsers\FindAllUsersResponse;
use App\Blog\Application\User\FindAllUsers\FindAllUsersService;
use App\Blog\Application\User\FindUser\FindUserRequest;
use App\Blog\Application\User\FindUser\FindUserResponse;
use App\Blog\Application\User\FindUser\FindUserService;
use App\Blog\Application\User\UpdateUser\UpdateUserRequest;
use App\Blog\Application\User\UpdateUser\UpdateUserService;
use App\Blog\Domain\Model\User\UserDoesNotExistException;
use App\Blog\Domain\Model\User\UserRepository;
use InvalidArgumentException;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\PhpRenderer;

class UserController
{
    private PhpRenderer $renderer;
    private UserRepository $userRepository;

    public function __construct(
        ContainerInterface $container,
        UserRepository $userRepository
    ) {
        $this->renderer = $container->get('renderer');
        $this->userRepository = $userRepository;
    }

    public function index(Request $request, Response $response): Response
    {
        $findAllUsersService = new FindAllUsersService($this->userRepository);
        $users = $findAllUsersService->execute();

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

        $createUserService = new CreateUserService($this->userRepository);
        $createUserRequest = new CreateUserRequest($userData['first_name'], $userData['last_name']);

        try {
            $createUserService->execute($createUserRequest);
        } catch (InvalidArgumentException $e) {
            $params = [
                'old' => $createUserRequest,
                'errors' => [$e->getMessage()],
            ];

            return $this->renderer->render($response->withStatus(422), 'User/create.phtml', $params);
        }

        return $response->withRedirect('/users');
    }

    public function edit(Request $request, Response $response, array $args): Response
    {
        $id = $args['id'];

        $findUserService = new FindUserService($this->userRepository);
        $findUserRequest = new FindUserRequest($id);

        try {
            $user = $findUserService->execute($findUserRequest);
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
            $updateUserService->execute($updateUserRequest);
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

        $deleteUserService = new DeleteUserService($this->userRepository);
        $deleteUserRequest = new DeleteUserRequest($id);

        try {
            $deleteUserService->execute($deleteUserRequest);
        } catch (UserDoesNotExistException $e) {
            return $response->write('User not found')->withStatus(404);
        }

        return $response->withRedirect('/users');
    }
}
