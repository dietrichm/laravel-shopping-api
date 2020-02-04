<?php

namespace Tests\Unit\Domain\Orders;

use Domain\Orders\LineItem;
use Domain\Orders\LineItemCollection;
use Domain\Orders\LineItemId;
use Domain\Products\ProductId;
use PHPUnit\Framework\TestCase;

final class LineItemCollectionTest extends TestCase
{
    /**
     * @var LineItemCollection
     */
    private $collection;

    public function setUp(): void
    {
        $this->collection = new LineItemCollection();
    }

    /**
     * @test
     */
    public function itCanAddLineItems(): void
    {
        $lineItem = $this->givenThereIsALineItem();

        $this->collection->add($lineItem);

        $this->assertTrue(
            $this->collection->has($lineItem->getId())
        );
    }

    /**
     * @test
     */
    public function itCanRemoveSpecifiedLineItem(): void
    {
        $lineItemOne = $this->givenThereIsALineItem();
        $lineItemTwo = $this->givenThereIsALineItem();

        $this->collection->add($lineItemOne);
        $this->collection->add($lineItemTwo);

        $this->collection->remove($lineItemOne->getId());

        $this->assertFalse(
            $this->collection->has($lineItemOne->getId())
        );
        $this->assertTrue(
            $this->collection->has($lineItemTwo->getId())
        );
    }

    private function givenThereIsALineItem(): LineItem
    {
        return new LineItem(
            LineItemId::generate(),
            ProductId::generate()
        );
    }
}
