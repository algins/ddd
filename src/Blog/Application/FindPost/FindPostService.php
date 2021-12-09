<?php

namespace App\Blog\Application\FindPost;

use App\Blog\Domain\Model\Post\PostRepository;

class FindPostService
{
    private PostRepository $postRepository;

    public function __construct(PostRepository $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    public function execute(string $id): FindPostResponse
    {
        $post = $this->postRepository->findById($id);

        return new FindPostResponse($post);
    }
}
