<?php

namespace App\Events;

use RuntimeException;
use Spatie\EventSourcing\EventSerializers\EventSerializer;
use Spatie\EventSourcing\ShouldBeStored;

final class JsonEventSerializer implements EventSerializer
{
    public function serialize(ShouldBeStored $event): string
    {
        if (!$event instanceof StoredEvent) {
            throw new RuntimeException('Event must be a StoredEvent');
        }

        return json_encode(
            $event->toArray()
        );
    }

    public function deserialize(string $eventClass, string $json): ShouldBeStored
    {
        return $eventClass::fromArray(
            json_decode($json, true)
        );
    }
}
