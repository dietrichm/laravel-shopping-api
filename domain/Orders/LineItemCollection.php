<?php

namespace Domain\Orders;

use Illuminate\Support\Collection;

final class LineItemCollection
{
    /**
     * @var Collection
     */
    private $collection;

    public function __construct()
    {
        $this->collection = new Collection();
    }

    public function add(LineItem $lineItem): void
    {
        $this->collection->offsetSet(
            $lineItem->getId()->toString(),
            $lineItem
        );
    }

    public function remove(LineItemId $lineItemId): void
    {
        $this->collection->offsetUnset($lineItemId->toString());
    }

    public function has(LineItemId $lineItemId): bool
    {
        return $this->collection->has($lineItemId->toString());
    }

    /**
     * @return LineItem[]
     */
    public function all(): array
    {
        return $this->collection->values()->all();
    }
}
