<?php

namespace Domain\Products;

use Ramsey\Uuid\Uuid;

final class ProductId
{
    /**
     * @var Uuid
     */
    private $uuid;

    public function __construct(Uuid $uuid)
    {
        $this->uuid = $uuid;
    }

    public static function generate(): self
    {
        return new self(Uuid::uuid4());
    }

    public function toString(): string
    {
        return $this->uuid->toString();
    }
}
