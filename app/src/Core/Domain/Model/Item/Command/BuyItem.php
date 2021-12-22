<?php

declare(strict_types=1);

namespace Core\Domain\Model\Item\Command;

use Assert\Assertion;
use Common\Domain\Command\BaseCommand;
use Core\Domain\Model\Item\ItemId;

final class BuyItem extends BaseCommand
{
    function withData(string $itemId): self
    {
        return new self([
            'id' => $itemId
        ]);
    }

    public function itemId(): ItemId
    {
        return ItemId::fromString($this->payload['item_id']);
    }

    public function validate(array $payload): void
    {
        Assertion::keyExists($payload, 'item_id');
        Assertion::uuid($payload['item_id']);
    }
}
