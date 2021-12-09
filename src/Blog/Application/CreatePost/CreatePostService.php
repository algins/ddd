<?php

namespace App\Blog\Application\CreatePost;

use App\Blog\Domain\Model\Post\Post;
use App\Blog\Domain\Model\Post\PostAuthor;
use App\Blog\Domain\Model\Post\PostRepository;

class CreatePostService
{
    private PostRepository $postRepository;

    public function __construct(PostRepository $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    public function execute(CreatePostRequest $request): void
    {
        $postAuthor = new PostAuthor('John', 'Doe');
        $post = Post::writeNewFrom($request->getTitle(), $request->getContent(), $postAuthor);

        $this->postRepository->save($post);
    }
}
