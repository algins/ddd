<?php

namespace App\Blog\Application\UpdatePost;

use App\Blog\Domain\Model\Post\Post;
use App\Blog\Domain\Model\Post\PostRepository;

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
