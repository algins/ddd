<?php

namespace App\Post\UI\Controllers;

use App\Post\Domain\Post;
use App\Post\Domain\PostRepository;
use App\Post\Domain\ValueObjects\PostAuthor;
use IllegalArgumentException;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\PhpRenderer;
use Valitron\Validator;

class PostController
{
    private PhpRenderer $renderer;
    private PostRepository $repository;

    public function __construct(ContainerInterface $container, PostRepository $repository)
    {
        $this->renderer = $container->get('renderer');
        $this->repository = $repository;
    }

    public function index(Request $request, Response $response): Response
    {
        $posts = $this->repository->findAll();

        $params = [
            'posts' => $posts,
        ];

        return $this->renderer->render($response, 'index.phtml', $params);
    }

    public function create(Request $request, Response $response): Response
    {
        $params = [
            'postData' => [],
            'errors' => [],
        ];

        return $this->renderer->render($response, 'create.phtml', $params);
    }

    public function store(Request $request, Response $response): Response
    {
        $postData = $request->getParsedBodyParam('post');

        $validator = new Validator($postData);
        $validator->rule('required', ['title', 'content']);

        if ($validator->validate()) {
            $postAuthor = new PostAuthor('John', 'Doe');
            $post = Post::writeNewFrom($postData['title'], $postData['content'], $postAuthor);
            $this->repository->save($post);
            return $response->withRedirect('/posts');
        }

        $params = [
            'postData' => $postData,
            'errors' => $validator->errors(),
        ];

        return $this->renderer->render($response->withStatus(422), 'create.phtml', $params);
    }

    public function edit(Request $request, Response $response, array $args): Response
    {
        $id = $args['id'];

        try {
            $post = $this->repository->findById($id);
        } catch (IllegalArgumentException $e) {
            return $response->write('Post not found')->withStatus(404);
        }

        $params = [
            'postData' => [
                'id' => $id,
                'title' => $post->getTitle(),
                'content' => $post->getContent(),
            ],
            'errors' => [],
        ];

        return $this->renderer->render($response, 'edit.phtml', $params);
    }

    public function update(Request $request, Response $response, array $args): Response
    {
        $id = $args['id'];

        try {
            $post = $this->repository->findById($id);
        } catch (IllegalArgumentException $e) {
            return $response->write('Post not found')->withStatus(404);
        }

        $postData = $request->getParsedBodyParam('post');

        $validator = new Validator($postData);
        $validator->rule('required', ['title', 'content']);

        if ($validator->validate()) {
            $post->changeTitleFor($postData['title']);
            $post->changeContentFor($postData['content']);
            $this->repository->save($post);
            return $response->withRedirect('/posts');
        }

        $params = [
            'postData' => [
                'id' => $id,
                'title' => $postData['title'],
                'content' => $postData['content'],
            ],
            'errors' => $validator->errors(),
        ];

        return $this->renderer->render($response->withStatus(422), 'edit.phtml', $params);
    }

    public function destroy(Request $request, Response $response, array $args): Response
    {
        $id = $args['id'];

        try {
            $post = $this->repository->findById($id);
        } catch (IllegalArgumentException $e) {
            return $response->write('Post not found')->withStatus(404);
        }

        $this->repository->delete($post);

        return $response->withRedirect('/posts');
    }
}
