<?php

namespace App\Domain\Repositories;

use App\Domain\Post;
use App\Infrastructure\Projections\Projector;
use IllegalArgumentException;

class SessionPostRepository implements PostRepository
{
    private Projector $projector;

    public function __construct(Projector $projector)
    {
        $this->projector = $projector;

        session_start();

        if (!array_key_exists('posts', $_SESSION)) {
            $_SESSION['posts'] = [];
        }
    }

    public function findAll(): array
    {
        return array_map(function ($post) {
            return Post::recreateFrom($post['id'], $post['title'], $post['content']);
        }, $_SESSION['posts']);
    }

    public function findById(string $id): Post
    {
        $post = $_SESSION['posts'][$id] ?? null;

        if (!$post) {
            throw new IllegalArgumentException();
        }

        return Post::recreateFrom($post['id'], $post['title'], $post['content']);
    }

    public function save(Post $post): void
    {
        $_SESSION['posts'][$post->getId()] = [
            'id' => $post->getId(),
            'title' => $post->getTitle(),
            'content' => $post->getContent(),
        ];

        $this->projector->project($post->recordedEvents());
    }

    public function delete(Post $post): void
    {
        unset($_SESSION['posts'][$post->getId()]);

        $this->projector->project($post->recordedEvents());
    }
}
