<?php

namespace App\Blog\Application\Post\ViewPost;

use App\Blog\Domain\Model\Post\PostDoesNotExistException;
use App\Blog\Domain\Model\Post\PostRepository;
use App\Blog\Domain\Model\User\UserRepository;

class ViewPostService
{
    private PostRepository $postRepository;
    private UserRepository $userRepository;

    public function __construct(PostRepository $postRepository, UserRepository $userRepository)
    {
        $this->postRepository = $postRepository;
        $this->userRepository = $userRepository;
    }

    public function execute(ViewPostRequest $request): ViewPostResponse
    {
        $id = $request->getId();
        $post = $this->postRepository->findById($id);

        if (!$post) {
            throw new PostDoesNotExistException();
        }

        $author = $this->userRepository->findById($post->getAuthorId()->getValue());

        return new ViewPostResponse($post, $author);
    }
}
