<?php

declare(strict_types=1);

namespace Core\Domain\Model\Item;

interface ItemCollectionInterface
{
    public function save(Item $item): void;

    public function get(ItemId $itemId): ?Item;
}
