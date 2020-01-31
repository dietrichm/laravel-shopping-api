<?php

namespace Tests\Feature\Http\Controllers\Products;

use Domain\Products\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class IndexControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function itReturnsProductsAsJson(): void
    {
        /** @var Product[] $products */
        $products = factory(Product::class, 2)->create();

        $response = $this->getJson('/api/products');

        $response->assertJsonCount(2, 'data');

        $response->assertJsonPath('data.0', [
            'id' => $products[0]->getId()->toString(),
            'name' => $products[0]->getName()->toString(),
            'price' => $products[0]->getPrice()->getValue(),
        ]);

        $response->assertJsonPath('data.1', [
            'id' => $products[1]->getId()->toString(),
            'name' => $products[1]->getName()->toString(),
            'price' => $products[1]->getPrice()->getValue(),
        ]);
    }
}
