<?php

declare(strict_types=1);

namespace Core\Domain\Model\Item\Command;

use Assert\Assertion;
use Common\Domain\Command\BaseCommand;
use Core\Domain\Model\Item\ItemId;
use Core\Domain\Model\Item\ItemAmount;

final class UpdateItemAmount extends BaseCommand
{
    function withData(string $itemId, int $amount): self
    {
        return new self([
            'id' => $itemId,
            'amount' => $amount
        ]);
    }

    public function itemId(): ItemId
    {
        return ItemId::fromString($this->payload['item_id']);
    }

    public function amount(): ItemAmount
    {
        return ItemAmount::fromInteger($this->payload['amount']);
    }

    public function validate(array $payload): void
    {
        Assertion::keyExists($payload, 'item_id');
        Assertion::uuid($payload['item_id']);
        Assertion::keyExists($payload, 'amount');
        Assertion::integer($payload['amount']);
    }
}
