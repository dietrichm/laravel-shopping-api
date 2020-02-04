<?php

namespace Domain\Orders;

use Illuminate\Support\Collection;
use RuntimeException;

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

    public function remove(LineItemId $lineItemId): LineItem
    {
        if (!$this->collection->offsetExists($lineItemId->toString())) {
            throw new RuntimeException('LineItemId is not present');
        }

        $lineItem = $this->collection->offsetGet($lineItemId->toString());

        $this->collection->offsetUnset($lineItemId->toString());

        return $lineItem;
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
