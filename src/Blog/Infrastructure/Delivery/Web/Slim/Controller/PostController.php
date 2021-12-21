<?php

namespace App\Blog\Infrastructure\Delivery\Web\Slim\Controller;

use App\Blog\Application\Post\MakePost\MakePostRequest;
use App\Blog\Application\Post\MakePost\MakePostService;
use App\Blog\Application\Post\RemovePost\RemovePostRequest;
use App\Blog\Application\Post\RemovePost\RemovePostService;
use App\Blog\Application\Post\UpdatePost\UpdatePostRequest;
use App\Blog\Application\Post\UpdatePost\UpdatePostService;
use App\Blog\Application\Post\ViewPost\ViewPostRequest;
use App\Blog\Application\Post\ViewPost\ViewPostResponse;
use App\Blog\Application\Post\ViewPost\ViewPostService;
use App\Blog\Application\Post\ViewPosts\ViewPostsResponse;
use App\Blog\Application\Post\ViewPosts\ViewPostsService;
use App\Blog\Application\User\ViewUsers\ViewUsersService;
use App\Blog\Domain\Model\Post\PostDoesNotExistException;
use App\Blog\Domain\Model\Post\PostRepository;
use App\Blog\Domain\Model\User\UserRepository;
use InvalidArgumentException;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\PhpRenderer;

class PostController
{
    private PhpRenderer $renderer;
    private PostRepository $postRepository;
    private UserRepository $userRepository;

    public function __construct(
        ContainerInterface $container,
        PostRepository $postRepository,
        UserRepository $userRepository
    ) {
        $this->renderer = $container->get('renderer');
        $this->postRepository = $postRepository;
        $this->userRepository = $userRepository;
    }

    public function index(Request $request, Response $response): Response
    {
        $viewPostsService = new ViewPostsService($this->postRepository, $this->userRepository);
        $posts = $viewPostsService->execute();

        $params = [
            'posts' => $posts,
        ];

        return $this->renderer->render($response, 'Post/index.phtml', $params);
    }

    public function create(Request $request, Response $response): Response
    {
        $viewUsersService = new ViewUsersService($this->userRepository);
        $users = $viewUsersService->execute();

        $params = [
            'post' => null,
            'users' => $users,
            'errors' => [],
        ];

        return $this->renderer->render($response, 'Post/create.phtml', $params);
    }

    public function store(Request $request, Response $response): Response
    {
        $postData = $request->getParsedBodyParam('post');

        $makePostService = new MakePostService($this->postRepository, $this->userRepository);
        $makePostRequest = new MakePostRequest($postData['title'], $postData['content'], $postData['author_id']);

        try {
            $makePostService->execute($makePostRequest);
        } catch (InvalidArgumentException $e) {
            $viewUsersService = new ViewUsersService($this->userRepository);
            $users = $viewUsersService->execute();

            $params = [
                'old' => $makePostRequest,
                'users' => $users,
                'errors' => [$e->getMessage()],
            ];

            return $this->renderer->render($response->withStatus(422), 'Post/create.phtml', $params);
        }

        return $response->withRedirect('/posts');
    }

    public function edit(Request $request, Response $response, array $args): Response
    {
        $id = $args['id'];

        $viewPostService = new ViewPostService($this->postRepository, $this->userRepository);
        $viewPostRequest = new ViewPostRequest($id);

        try {
            $post = $viewPostService->execute($viewPostRequest);
        } catch (PostDoesNotExistException $e) {
            return $response->write('Post not found')->withStatus(404);
        }

        $params = [
            'post' => $post,
            'errors' => [],
        ];

        return $this->renderer->render($response, 'Post/edit.phtml', $params);
    }

    public function update(Request $request, Response $response, array $args): Response
    {
        $id = $args['id'];
        $postData = $request->getParsedBodyParam('post');

        $updatePostService = new UpdatePostService($this->postRepository);
        $updatePostRequest = new UpdatePostRequest($id, $postData['title'], $postData['content']);

        try {
            $updatePostService->execute($updatePostRequest);
        } catch (PostDoesNotExistException $e) {
            return $response->write('Post not found')->withStatus(404);
        } catch (InvalidArgumentException $e) {
            $params = [
                'old' => $updatePostRequest,
                'errors' => [$e->getMessage()],
            ];

            return $this->renderer->render($response->withStatus(422), 'Post/edit.phtml', $params);
        }

        return $response->withRedirect('/posts');
    }

    public function destroy(Request $request, Response $response, array $args): Response
    {
        $id = $args['id'];

        $removePostService = new RemovePostService($this->postRepository);
        $removePostRequest = new RemovePostRequest($id);

        try {
            $removePostService->execute($removePostRequest);
        } catch (PostDoesNotExistException $e) {
            return $response->write('Post not found')->withStatus(404);
        }

        return $response->withRedirect('/posts');
    }
}
