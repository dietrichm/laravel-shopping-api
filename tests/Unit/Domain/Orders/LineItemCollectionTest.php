<?php

namespace Tests\Unit\Domain\Orders;

use App\ValueObjects\Money;
use Domain\Orders\LineItem;
use Domain\Orders\LineItemCollection;
use Domain\Orders\LineItemId;
use Domain\Products\Product;
use RuntimeException;
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
    public function itCanReturnAndRemoveSpecifiedLineItem(): void
    {
        $lineItemOne = $this->givenThereIsALineItem();
        $lineItemTwo = $this->givenThereIsALineItem();

        $this->collection->add($lineItemOne);
        $this->collection->add($lineItemTwo);

        $returnedLineItem = $this->collection->remove($lineItemOne->getId());

        $this->assertEquals($lineItemOne, $returnedLineItem);

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
    public function itThrowsWhenRemovingUnknownLineItem(): void
    {
        $this->expectException(RuntimeException::class);

        $this->collection->remove(LineItemId::generate());
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

    /**
     * @test
     */
    public function itCanCalculateTotalPrice(): void
    {
        $priceOne = Money::fromValue(12.54);
        $priceTwo = Money::fromValue(892);

        $lineItemOne = $this->givenThereIsALineItem([
            'price' => $priceOne->getValue(),
        ]);
        $lineItemTwo = $this->givenThereIsALineItem([
            'price' => $priceTwo->getValue(),
        ]);

        $this->collection->add($lineItemOne);
        $this->collection->add($lineItemTwo);

        $this->assertEquals(
            Money::fromValue(904.54),
            $this->collection->getTotalPrice()
        );
    }

    private function givenThereIsALineItem(
        array $productProperties = []
    ): LineItem {
        return new LineItem(
            LineItemId::generate(),
            factory(Product::class)->make($productProperties)
        );
    }
}
