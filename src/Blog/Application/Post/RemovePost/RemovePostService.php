<?php

namespace App\Blog\Application\Post\RemovePost;

use App\Blog\Domain\Model\Post\PostDoesNotExistException;
use App\Blog\Domain\Model\Post\PostRepository;

class RemovePostService
{
    private PostRepository $postRepository;

    public function __construct(PostRepository $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    public function execute(RemovePostRequest $request): void
    {
        $id = $request->getId();
        $post = $this->postRepository->findById($id);

        if (!$post) {
            throw new PostDoesNotExistException();
        }

        $post->markAsDeleted();

        $this->postRepository->save($post);
    }
}
