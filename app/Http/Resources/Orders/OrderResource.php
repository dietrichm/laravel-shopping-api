<?php

namespace App\Http\Resources\Orders;

use App\Http\Resources\Products\ProductResource;
use Domain\Orders\LineItem;
use Domain\Orders\LineItemCollection;
use Domain\Orders\Order;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class OrderResource extends JsonResource
{
    /**
     * @param Request $request
     */
    public function toArray($request): array
    {
        /** @var Order $order */
        $order = $this->resource;

        return [
            'id' => $order->getId()->toString(),
            'totalPrice' => $order->getTotalPrice()->getValue(),
            'lineItems' => $this->transformLineItems(
                $order->getLineItems(),
                $request
            ),
            'removedLineItems' => $this->transformLineItems(
                $order->getRemovedLineItems(),
                $request
            ),
        ];
    }

    private function transformLineItems(
        LineItemCollection $lineItems,
        Request $request
    ): array {
        return array_map(
            function (LineItem $lineItem) use ($request) {
                return $this->transformLineItem($lineItem, $request);
            },
            $lineItems->all()
        );
    }

    private function transformLineItem(
        LineItem $lineItem,
        Request $request
    ): array {
        $productResource = new ProductResource($lineItem->getProduct());

        return [
            'id' => $lineItem->getId()->toString(),
            'product' => $productResource->toArray($request),
        ];
    }
}
