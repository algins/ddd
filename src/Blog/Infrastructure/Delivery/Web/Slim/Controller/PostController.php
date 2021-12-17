<?php

namespace App\Blog\Infrastructure\Delivery\Web\Slim\Controller;

use App\Blog\Application\Post\CreatePost\CreatePostRequest;
use App\Blog\Application\Post\CreatePost\CreatePostService;
use App\Blog\Application\Post\DeletePost\DeletePostRequest;
use App\Blog\Application\Post\DeletePost\DeletePostService;
use App\Blog\Application\Post\FindAllPosts\FindAllPostsResponse;
use App\Blog\Application\Post\FindAllPosts\FindAllPostsService;
use App\Blog\Application\Post\FindPost\FindPostRequest;
use App\Blog\Application\Post\FindPost\FindPostResponse;
use App\Blog\Application\Post\FindPost\FindPostService;
use App\Blog\Application\Post\UpdatePost\UpdatePostRequest;
use App\Blog\Application\Post\UpdatePost\UpdatePostService;
use App\Blog\Application\User\FindAllUsers\FindAllUsersService;
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
        $findAllPostsService = new FindAllPostsService($this->postRepository, $this->userRepository);
        $posts = $findAllPostsService->execute();

        $params = [
            'posts' => $posts,
        ];

        return $this->renderer->render($response, 'Post/index.phtml', $params);
    }

    public function create(Request $request, Response $response): Response
    {
        $findAllUsersService = new FindAllUsersService($this->userRepository);
        $users = $findAllUsersService->execute();

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

        $createPostService = new CreatePostService($this->postRepository, $this->userRepository);
        $createPostRequest = new CreatePostRequest($postData['title'], $postData['content'], $postData['author_id']);

        try {
            $createPostService->execute($createPostRequest);
        } catch (InvalidArgumentException $e) {
            $findAllUsersService = new FindAllUsersService($this->userRepository);
            $users = $findAllUsersService->execute();

            $params = [
                'old' => $createPostRequest,
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

        $findPostService = new FindPostService($this->postRepository, $this->userRepository);
        $findPostRequest = new FindPostRequest($id);

        try {
            $post = $findPostService->execute($findPostRequest);
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

        $deletePostService = new DeletePostService($this->postRepository);
        $deletePostRequest = new DeletePostRequest($id);

        try {
            $deletePostService->execute($deletePostRequest);
        } catch (PostDoesNotExistException $e) {
            return $response->write('Post not found')->withStatus(404);
        }

        return $response->withRedirect('/posts');
    }
}
