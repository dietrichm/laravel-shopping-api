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
}
