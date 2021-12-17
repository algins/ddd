<?php

namespace App\Blog\Application\Post\DeletePost;

use App\Blog\Domain\Model\Post\PostDoesNotExistException;
use App\Blog\Domain\Model\Post\PostRepository;

class DeletePostService
{
    private PostRepository $postRepository;

    public function __construct(PostRepository $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    public function execute(DeletePostRequest $request): void
    {
        $id = $request->getId();
        $post = $this->postRepository->findById($id);

        if (!$post) {
            throw new PostDoesNotExistException();
        }

        $this->postRepository->delete($post);
    }
}
