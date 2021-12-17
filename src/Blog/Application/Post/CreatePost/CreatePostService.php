<?php

namespace App\Blog\Application\Post\CreatePost;

use App\Blog\Domain\Model\Post\PostRepository;
use App\Blog\Domain\Model\User\UserRepository;
use InvalidArgumentException;

class CreatePostService
{
    private PostRepository $postRepository;
    private UserRepository $userRepository;

    public function __construct(PostRepository $postRepository, UserRepository $userRepository)
    {
        $this->postRepository = $postRepository;
        $this->userRepository = $userRepository;
    }

    public function execute(CreatePostRequest $request): void
    {
        $title = $request->getTitle();
        $content = $request->getContent();
        $authorId = $request->getAuthorId();
        $author = $this->userRepository->findById($authorId);

        if (!$author) {
            throw new InvalidArgumentException('Empty author');
        }

        $post = $author->createPost($title, $content);

        $this->postRepository->save($post);
    }
}
