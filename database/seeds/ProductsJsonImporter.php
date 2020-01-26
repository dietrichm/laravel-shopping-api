<?php

use Domain\Products\Money;
use Domain\Products\Product;
use Domain\Products\ProductId;
use Domain\Products\ProductName;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

final class ProductsJsonImporter extends Seeder
{
    public function run(): void
    {
        $productsJsonFile = File::get(
            __DIR__ . '/products.json'
        );

        foreach (json_decode($productsJsonFile, true) as $productData) {
            $product = new Product();

            $product->setId(ProductId::generate());
            $product->setName(ProductName::fromString($productData['name']));
            $product->setPrice(Money::fromValue($productData['price']));

            $product->save();
        }
    }
}
