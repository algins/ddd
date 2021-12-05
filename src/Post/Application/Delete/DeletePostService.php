<?php

namespace App\Post\Application\Delete;

use App\Post\Domain\PostRepository;

class DeletePostService
{
    private PostRepository $postRepository;

    public function __construct(PostRepository $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    public function execute(string $id): void
    {
        $post = $this->postRepository->findById($id);

        $this->postRepository->delete($post);
    }
}
