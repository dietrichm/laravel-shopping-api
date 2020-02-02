<?php

namespace App\ValueObjects;

use Ramsey\Uuid\Uuid;

abstract class UuidIdentifier
{
    /**
     * @var Uuid
     */
    private $uuid;

    public function __construct(Uuid $uuid)
    {
        $this->uuid = $uuid;
    }

    public static function fromString(string $value): self
    {
        return new static(Uuid::fromString($value));
    }

    public static function generate(): self
    {
        return new static(Uuid::uuid4());
    }

    public function toString(): string
    {
        return $this->uuid->toString();
    }
}
