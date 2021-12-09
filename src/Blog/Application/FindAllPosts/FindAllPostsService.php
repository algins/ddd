<?php

namespace App\Blog\Application\FindAllPosts;

use App\Blog\Domain\Model\Post\PostRepository;

class FindAllPostsService
{
    private PostRepository $postRepository;

    public function __construct(PostRepository $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    public function execute(): array
    {
        $posts = $this->postRepository->findAll();

        return array_map(function ($post) {
            return new FindAllPostsResponse($post);
        }, $posts);
    }
}
