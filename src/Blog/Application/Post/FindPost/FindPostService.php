<?php

namespace App\Blog\Application\Post\FindPost;

use App\Blog\Domain\Model\Post\PostDoesNotExistException;
use App\Blog\Domain\Model\Post\PostRepository;
use App\Blog\Domain\Model\User\UserRepository;

class FindPostService
{
    private PostRepository $postRepository;
    private UserRepository $userRepository;

    public function __construct(PostRepository $postRepository, UserRepository $userRepository)
    {
        $this->postRepository = $postRepository;
        $this->userRepository = $userRepository;
    }

    public function execute(FindPostRequest $request): FindPostResponse
    {
        $id = $request->getId();
        $post = $this->postRepository->findById($id);

        if (!$post) {
            throw new PostDoesNotExistException();
        }

        $author = $this->userRepository->findById($post->getAuthorId()->getValue());

        return new FindPostResponse($post, $author);
    }
}
