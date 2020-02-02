<?php

namespace Tests\Unit\App\Events;

use App\Events\StoredEvent;

final class DummyEvent implements StoredEvent
{
    /**
     * @var string
     */
    private $foo;

    /**
     * @var float
     */
    private $bar;

    public function __construct(string $foo, float $bar)
    {
        $this->foo = $foo;
        $this->bar = $bar;
    }

    public function toArray(): array
    {
        return [
            'foo' => $this->foo,
            'bar' => $this->bar,
        ];
    }

    public static function fromArray(array $data): StoredEvent
    {
        return new self(
            $data['foo'],
            $data['bar']
        );
    }
}
