<?php

namespace App;

use IllegalArgumentException;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\PhpRenderer;
use Valitron\Validator;

class PostController
{
    private PhpRenderer $renderer;
    private PostService $service;

    public function __construct(ContainerInterface $container, PostService $service) {
        $this->renderer = $container->get('renderer');
        $this->service = $service;
    }

    public function index(Request $request, Response $response): Response
    {
        $posts = $this->service->findAll();

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
            $postId = uniqid();
            $post = new Post($postId, $postData['title'], $postData['content']);
            $this->service->save($post);

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
            $post = $this->service->findById($id);
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
            $post = $this->service->findById($id);
        } catch (IllegalArgumentException $e) {
            return $response->write('Post not found')->withStatus(404);
        }

        $postData = $request->getParsedBodyParam('post');

        $validator = new Validator($postData);
        $validator->rule('required', ['title', 'content']);

        if ($validator->validate()) {
            $postId = $post->getId();
            $post = new Post($postId, $postData['title'], $postData['content']);
            $this->service->save($post);

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
            $this->service->deleteById($id);
        } catch (IllegalArgumentException $e) {
            return $response->write('Post not found')->withStatus(404);
        }

        return $response->withRedirect('/posts');
    }
}