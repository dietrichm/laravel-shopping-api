<?php

namespace Tests\Unit\Domain\Orders;

use Domain\Orders\LineItem;
use Domain\Orders\LineItemCollection;
use Domain\Orders\LineItemId;
use Domain\Products\Product;
use Tests\TestCase;

final class LineItemCollectionTest extends TestCase
{
    /**
     * @var LineItemCollection
     */
    private $collection;

    public function setUp(): void
    {
        parent::setUp();

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

    /**
     * @test
     */
    public function itCanReturnAllLineItems(): void
    {
        $lineItemOne = $this->givenThereIsALineItem();
        $lineItemTwo = $this->givenThereIsALineItem();

        $this->assertEmpty($this->collection->all());

        $this->collection->add($lineItemOne);
        $this->collection->add($lineItemTwo);

        $this->assertEquals(
            [
                $lineItemOne,
                $lineItemTwo,
            ],
            $this->collection->all()
        );
    }

    private function givenThereIsALineItem(): LineItem
    {
        return new LineItem(
            LineItemId::generate(),
            factory(Product::class)->make()
        );
    }
}
