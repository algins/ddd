<?php

namespace App\Post\Application\Find;

use App\Post\Application\PostResponse;
use App\Post\Domain\PostRepository;

class FindPostService
{
    private PostRepository $postRepository;

    public function __construct(PostRepository $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    public function execute(string $id): PostResponse
    {
        $post = $this->postRepository->findById($id);

        return new PostResponse($post);
    }
}
