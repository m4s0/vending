<?php

declare(strict_types=1);

namespace Core\Domain\Model\Item\Command;

use Assert\Assertion;
use Common\Domain\Command\BaseCommand;
use Core\Domain\Model\Item\ItemId;
use Core\Domain\Model\Item\ItemPrice;

final class UpdateItemPrice extends BaseCommand
{
    function withData(string $itemId, float $price): self
    {
        return new self([
            'id' => $itemId,
            'price' => $price
        ]);
    }

    public function itemId(): ItemId
    {
        return ItemId::fromString($this->payload['item_id']);
    }

    public function price(): ItemPrice
    {
        return ItemPrice::fromFloat($this->payload['price']);
    }

    public function validate(array $payload): void
    {
        Assertion::keyExists($payload, 'item_id');
        Assertion::uuid($payload['item_id']);
        Assertion::keyExists($payload, 'price');
        Assertion::float($payload['price']);
    }
}
