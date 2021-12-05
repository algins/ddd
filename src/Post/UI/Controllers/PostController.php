<?php

namespace App\Post\UI\Controllers;

use App\Post\Application\Create\CreatePostRequest;
use App\Post\Application\Create\CreatePostService;
use App\Post\Application\Delete\DeletePostService;
use App\Post\Application\Find\FindPostService;
use App\Post\Application\FindAll\FindAllPostsService;
use App\Post\Application\PostResponse;
use App\Post\Application\Update\UpdatePostRequest;
use App\Post\Application\Update\UpdatePostService;
use App\Post\Domain\PostRepository;
use IllegalArgumentException;
use InvalidArgumentException;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\PhpRenderer;

class PostController
{
    private PhpRenderer $renderer;
    private PostRepository $postRepository;

    public function __construct(ContainerInterface $container, PostRepository $postRepository)
    {
        $this->renderer = $container->get('renderer');
        $this->postRepository = $postRepository;
    }

    public function index(Request $request, Response $response): Response
    {
        $findAllPostsService = new FindAllPostsService($this->postRepository);
        $posts = $findAllPostsService->execute();

        $params = [
            'posts' => $posts,
        ];

        return $this->renderer->render($response, 'index.phtml', $params);
    }

    public function create(Request $request, Response $response): Response
    {
        $params = [
            'post' => null,
            'errors' => [],
        ];

        return $this->renderer->render($response, 'create.phtml', $params);
    }

    public function store(Request $request, Response $response): Response
    {
        $postData = $request->getParsedBodyParam('post');
        $createPostService = new CreatePostService($this->postRepository);
        $createPostRequest = new CreatePostRequest($postData['title'], $postData['content']);

        try {
            $createPostService->execute($createPostRequest);
        } catch (InvalidArgumentException $e) {
            $params = [
                'post' => $createPostRequest,
                'errors' => [$e->getMessage()],
            ];

            return $this->renderer->render($response->withStatus(422), 'create.phtml', $params);
        }

        return $response->withRedirect('/posts');
    }

    public function edit(Request $request, Response $response, array $args): Response
    {
        $id = $args['id'];
        $findPostService = new FindPostService($this->postRepository);

        try {
            $post = $findPostService->execute($id);
        } catch (IllegalArgumentException $e) {
            return $response->write('Post not found')->withStatus(404);
        }

        $params = [
            'post' => $post,
            'errors' => [],
        ];

        return $this->renderer->render($response, 'edit.phtml', $params);
    }

    public function update(Request $request, Response $response, array $args): Response
    {
        $id = $args['id'];
        $postData = $request->getParsedBodyParam('post');
        $updatePostService = new UpdatePostService($this->postRepository);
        $updatePostRequest = new UpdatePostRequest($id, $postData['title'], $postData['content']);

        try {
            $updatePostService->execute($updatePostRequest);
        } catch (IllegalArgumentException $e) {
            return $response->write('Post not found')->withStatus(404);
        } catch (InvalidArgumentException $e) {
            $params = [
                'post' => $updatePostRequest,
                'errors' => [$e->getMessage()],
            ];

            return $this->renderer->render($response->withStatus(422), 'edit.phtml', $params);
        }

        return $response->withRedirect('/posts');
    }

    public function destroy(Request $request, Response $response, array $args): Response
    {
        $id = $args['id'];
        $deletePostService = new DeletePostService($this->postRepository);

        try {
            $deletePostService->execute($id);
        } catch (IllegalArgumentException $e) {
            return $response->write('Post not found')->withStatus(404);
        }

        return $response->withRedirect('/posts');
    }
}
