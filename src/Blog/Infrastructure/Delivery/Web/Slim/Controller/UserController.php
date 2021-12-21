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
use InvalidArgumentException;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\PhpRenderer;

class UserController
{
    private PhpRenderer $renderer;
    private MakeUserService $makeUserService;
    private RemoveUserService $removeUserService;
    private UpdateUserService $updateUserService;
    private ViewUserService $viewUserService;
    private ViewUsersService $viewUsersService;

    public function __construct(
        ContainerInterface $container,
        MakeUserService $makeUserService,
        RemoveUserService $removeUserService,
        UpdateUserService $updateUserService,
        ViewUserService $viewUserService,
        ViewUsersService $viewUsersService,
    ) {
        $this->renderer = $container->get('renderer');
        $this->makeUserService = $makeUserService;
        $this->removeUserService = $removeUserService;
        $this->updateUserService = $updateUserService;
        $this->viewUserService = $viewUserService;
        $this->viewUsersService = $viewUsersService;
    }

    public function index(Request $request, Response $response): Response
    {
        $users = $this->viewUsersService->execute();

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
        $makeUserRequest = new MakeUserRequest($userData['first_name'], $userData['last_name']);

        try {
            $this->makeUserService->execute($makeUserRequest);
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
        $viewUserRequest = new ViewUserRequest($id);

        try {
            $user = $this->viewUserService->execute($viewUserRequest);
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

        $updateUserRequest = new UpdateUserRequest($id, $userData['first_name'], $userData['last_name']);

        try {
            $user = $this->updateUserService->execute($updateUserRequest);
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
        $removeUserRequest = new RemoveUserRequest($id);

        try {
            $this->removeUserService->execute($removeUserRequest);
        } catch (UserDoesNotExistException $e) {
            return $response->write('User not found')->withStatus(404);
        }

        return $response->withRedirect('/users');
    }
}
