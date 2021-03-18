<?php

namespace App;

use DI\Container;
use Slim\Http\ServerRequest as Request;
use Slim\Http\Response;

/**
* Orchestrate and organize the View and the Model.
* Receive a HTTP request and return a HTTP response.
*/
class PostController
{
    private $container;
    private $service;

    public function __construct(Container $container, PostService $service)
    {
        $this->container = $container;
        $this->service = $service;
    }

    public function index(Request $request, Response $response)
    {
        $posts = $this->service->getAllPosts();
        $params = ['posts' => $posts];

        return $this->container
            ->get('renderer')
            ->render($response, 'index.phtml', $params);
    }

    public function create(Request $request, Response $response)
    {
        return $this->container
            ->get('renderer')
            ->render($response, 'create.phtml');
    }

    public function store(Request $request, Response $response)
    {
        $this->service->createPost(
            $request->getParsedBodyParam('title'),
            $request->getParsedBodyParam('body')
        );

        $redirectUrl = $this->container
            ->get('router')
            ->urlFor('index');

        return $response->withRedirect($redirectUrl);
    }

    public function show()
    {
        //
    }

    public function edit()
    {
        //
    }

    public function update(Request $request)
    {
        //
    }

    public function destroy()
    {
        //
    }
}