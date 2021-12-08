<?php

namespace App\Post\Application\CreatePost;

use App\Post\Domain\Post;
use App\Post\Domain\PostRepository;
use App\Post\Domain\ValueObjects\PostAuthor;

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
