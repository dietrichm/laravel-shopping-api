<?php

namespace Tests\Unit\App\Events;

use App\Events\JsonEventSerializer;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use Spatie\EventSourcing\ShouldBeStored;

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

    /**
     * @test
     */
    public function itDeserializesEvents(): void
    {
        $expectedEvent = new DummyEvent(
            'this',
            864.23
        );

        $event = $this->serializer->deserialize(
            DummyEvent::class,
            '{"foo":"this","bar":864.23}'
        );

        $this->assertEquals(
            $expectedEvent,
            $event
        );
    }

    /**
     * @test
     */
    public function itCannotSerializeNonStoredEvents(): void
    {
        $event = new class implements ShouldBeStored {
        };

        $this->expectException(RuntimeException::class);

        $this->serializer->serialize($event);
    }
}
