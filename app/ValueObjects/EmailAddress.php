<?php

namespace App\ValueObjects;

use RuntimeException;

final class EmailAddress
{
    /**
     * @var string
     */
    private $emailAddress;

    public function __construct(string $emailAddress)
    {
        if (filter_var($emailAddress, FILTER_VALIDATE_EMAIL) === false) {
            return new RuntimeException('Invalid email address provided');
        }

        $this->emailAddress = $emailAddress;
    }

    public static function fromString(string $emailAddress): self
    {
        return new self($emailAddress);
    }

    public function toString(): string
    {
        return $this->emailAddress;
    }

    public function equals(self $other): bool
    {
        return $this->emailAddress === $other->emailAddress;
    }
}
