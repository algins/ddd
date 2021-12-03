<?php

namespace App\Post\Domain\ValueObjects;

use Ramsey\Uuid\Uuid;

class PostId
{
    private string $value;

    private function __construct(?string $value = null)
    {
        $this->value = $value ?? Uuid::uuid4()->toString();
    }

    public static function create(?string $value = null): self
    {
        return new static($value);
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
