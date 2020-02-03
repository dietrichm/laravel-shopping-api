<?php

namespace Tests\Unit\App\Http\Resources\Orders;

use App\Http\Resources\Orders\LineItemIdResource;
use Domain\Orders\LineItemId;
use Illuminate\Http\Request;
use PHPUnit\Framework\TestCase;

final class LineItemIdResourceTest extends TestCase
{
    /**
     * @test
     */
    public function itTransformsLineItemIdIntoArray(): void
    {
        $lineItemId = LineItemId::generate();

        $resource = new LineItemIdResource($lineItemId);

        $this->assertEquals(
            [
                'id' => $lineItemId->toString(),
            ],
            $resource->toArray(new Request())
        );
    }
}
