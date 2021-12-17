<?php

namespace App\Blog\Application\Post\FindAllPosts;

use App\Blog\Domain\Model\Post\PostRepository;
use App\Blog\Domain\Model\User\UserRepository;

class FindAllPostsService
{
    private PostRepository $postRepository;
    private UserRepository $userRepository;

    public function __construct(PostRepository $postRepository, UserRepository $userRepository)
    {
        $this->postRepository = $postRepository;
        $this->userRepository = $userRepository;
    }

    public function execute(): array
    {
        $posts = $this->postRepository->findAll();

        return array_map(function ($post) {
            $author = $this->userRepository->findById($post->getAuthorId()->getValue());
            return new FindAllPostsResponse($post, $author);
        }, $posts);
    }
}
