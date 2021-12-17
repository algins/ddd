<?php

namespace App\Blog\Application\Post\UpdatePost;

use App\Blog\Domain\Model\Post\PostDoesNotExistException;
use App\Blog\Domain\Model\Post\PostRepository;
use InvalidArgumentException;

class UpdatePostService
{
    private PostRepository $postRepository;

    public function __construct(PostRepository $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    public function execute(UpdatePostRequest $request): void
    {
        $id = $request->getId();
        $title = $request->getTitle();
        $content = $request->getContent();
        $post = $this->postRepository->findById($id);

        if (!$post) {
            throw new PostDoesNotExistException();
        }

        $post->changeTitleFor($title);
        $post->changeContentFor($content);

        $this->postRepository->save($post);
    }
}
