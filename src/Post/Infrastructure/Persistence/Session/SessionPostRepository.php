<?php

namespace App\Post\Infrastructure\Persistence\Session;

use App\Post\Domain\Post;
use App\Post\Domain\PostRepository;
use App\Post\Domain\ValueObjects\PostAuthor;
use App\Shared\Infrastructure\Persistence\Projections\Projector;
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
            return Post::recreateFrom(
                $post['id'],
                $post['title'],
                $post['content'],
                new PostAuthor($post['author_first_name'], $post['author_last_name'])
            );
        }, $_SESSION['posts']);
    }

    public function findById(string $id): Post
    {
        $post = $_SESSION['posts'][$id] ?? null;

        if (!$post) {
            throw new IllegalArgumentException();
        }

        return Post::recreateFrom(
            $post['id'],
            $post['title'],
            $post['content'],
            new PostAuthor($post['author_first_name'], $post['author_last_name'])
        );
    }

    public function save(Post $post): void
    {
        $postAuthor = $post->getAuthor();

        $_SESSION['posts'][$post->getId()] = [
            'id' => $post->getId(),
            'title' => $post->getTitle(),
            'content' => $post->getContent(),
            'author_first_name' => $postAuthor->getFirstName(),
            'author_last_name' => $postAuthor->getLastName(),
        ];

        $this->projector->project($post->recordedEvents());
    }

    public function delete(Post $post): void
    {
        unset($_SESSION['posts'][$post->getId()]);

        $this->projector->project($post->recordedEvents());
    }
}
