<?php

namespace App;

use Psr\Container\ContainerInterface;

/**
* Controller (Controller layer). 
* Organizes and orchestrates the View and the Model.
* Receives HTTP request and returns HTTP response.
*/
class PostController
{
    private $renderer;

    public function __construct(ContainerInterface $container) {
        $this->renderer = $container->get('renderer');
    }

    public function __invoke($request, $response, $args)
    {
        $posts = (new PostService())->findAllPosts();

        $params = [
            'posts' => $posts,
        ];

        return $this->renderer->render($response, 'index.phtml', $params);
    }
}