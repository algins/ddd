<?php

namespace App\Blog\Infrastructure\Domain\Model\Post\Session;

use App\Blog\Domain\Model\Post\Post;
use App\Blog\Domain\Model\Post\PostAuthor;
use App\Blog\Domain\Model\Post\PostId;
use App\Blog\Domain\Model\Post\PostRepository;
use App\Shared\Infrastructure\Projection\Projector;
use IllegalArgumentException;

class SessionPostRepository implements PostRepository
{
    private Projector $projector;

    public function __construct(Projector $projector)
    {
        $this->projector = $projector;

        if (!isset($_SESSION)) {
            session_start();
        }

        if (!array_key_exists('posts', $_SESSION)) {
            $_SESSION['posts'] = [];
        }
    }

    public function findAll(): array
    {
        return array_map(function ($post) {
            return Post::fromRawData($post);
        }, $_SESSION['posts']);
    }

    public function findById(string $id): Post
    {
        $post = $_SESSION['posts'][$id] ?? null;

        if (!$post) {
            throw new IllegalArgumentException();
        }

        return Post::fromRawData($post);
    }

    public function save(Post $post): void
    {
        $postAuthor = $post->getAuthor();
        $postId = $post->getId()->getValue();

        $_SESSION['posts'][$postId] = [
            'id' => $postId,
            'title' => $post->getTitle(),
            'content' => $post->getContent(),
            'author_first_name' => $postAuthor->getFirstName(),
            'author_last_name' => $postAuthor->getLastName(),
        ];

        $this->projector->project($post->recordedEvents());
    }

    public function delete(Post $post): void
    {
        unset($_SESSION['posts'][$post->getId()->getValue()]);

        $this->projector->project($post->recordedEvents());
    }
}
