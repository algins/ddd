<?php

namespace App\Post\Application\UpdatePost;

use App\Post\Domain\Post;
use App\Post\Domain\PostRepository;

class UpdatePostService
{
    private PostRepository $postRepository;

    public function __construct(PostRepository $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    public function execute(UpdatePostRequest $request): void
    {
        $post = $this->postRepository->findById($request->getId());

        $post->changeTitleFor($request->getTitle());
        $post->changeContentFor($request->getContent());

        $this->postRepository->save($post);
    }
}
