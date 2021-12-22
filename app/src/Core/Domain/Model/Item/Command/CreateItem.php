<?php

declare(strict_types=1);

namespace Core\Domain\Model\Item\Command;

use Assert\Assertion;
use Common\Domain\Command\BaseCommand;
use Core\Domain\Model\Item\ItemId;
use Core\Domain\Model\Item\ItemName;
use Core\Domain\Model\Item\ItemPrice;
use Core\Domain\Model\Item\ItemAmount;

final class CreateItem extends BaseCommand
{
    function withData(string $itemId, string $name, float $price, int $amount): self
    {
        return new self([
            'id' => $itemId,
            'name' => $name,
            'price' => $price,
            'amount' => $amount
        ]);
    }

    public function itemId(): ItemId
    {
        return ItemId::fromString($this->payload['item_id']);
    }

    public function name(): ItemName
    {
        return ItemName::fromString($this->payload['name']);
    }

    public function price(): ItemPrice
    {
        return ItemPrice::fromFloat($this->payload['price']);
    }

    public function amount(): ItemAmount
    {
        return ItemAmount::fromInteger($this->payload['amount']);
    }

    public function validate(array $payload): void
    {
        Assertion::keyExists($payload, 'item_id');
        Assertion::uuid($payload['item_id']);
        Assertion::keyExists($payload, 'name');
        Assertion::string($payload['name']);
        Assertion::keyExists($payload, 'price');
        Assertion::float($payload['price']);
        Assertion::keyExists($payload, 'amount');
        Assertion::integer($payload['amount']);
    }
}
