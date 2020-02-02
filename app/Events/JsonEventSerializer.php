<?php

namespace App\Events;

use Spatie\EventSourcing\EventSerializers\EventSerializer;
use Spatie\EventSourcing\ShouldBeStored;

final class JsonEventSerializer implements EventSerializer
{
    public function serialize(ShouldBeStored $event): string
    {
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
