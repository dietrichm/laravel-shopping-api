<?php

namespace App\Events;

use Spatie\EventSourcing\ShouldBeStored;

interface StoredEvent extends ShouldBeStored
{
    public function toArray(): array;

    public static function fromArray(array $data): self;
}
