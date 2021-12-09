<?php

namespace App\Blog\Domain\Model\Post;

use InvalidArgumentException;
use Ramsey\Uuid\Uuid;

class PostId
{
    private string $value;

    private function __construct(?string $value)
    {
        $this->setValue($value);
    }

    public static function create(?string $value = null): self
    {
        return new static($value);
    }

    private function setValue(?string $value): void
    {
        if ($value) {
            $this->assertIsValidUuid($value);
        }

        $this->value = $value ?? Uuid::uuid4()->toString();
    }

    public function getValue(): string
    {
        return $this->value;
    }

    private function assertIsValidUuid(string $uuid): void
    {
        if (!Uuid::isValid($uuid)) {
            throw new InvalidArgumentException('Invalid UUID');
        }
    }
}
