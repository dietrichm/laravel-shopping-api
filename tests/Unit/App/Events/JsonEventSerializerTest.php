<?php

namespace Tests\Unit\App\Events;

use App\Events\JsonEventSerializer;
use PHPUnit\Framework\TestCase;

final class JsonEventSerializerTest extends TestCase
{
    /**
     * @var JsonEventSerializer
     */
    private $serializer;

    public function setUp(): void
    {
        $this->serializer = new JsonEventSerializer();
    }

    /**
     * @test
     */
    public function itSerializesEvents(): void
    {
        $event = new DummyEvent(
            'this',
            864.23
        );

        $serialized = $this->serializer->serialize($event);

        $this->assertEquals(
            '{"foo":"this","bar":864.23}',
            $serialized
        );
    }
}
