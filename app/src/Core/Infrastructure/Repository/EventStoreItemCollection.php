<?php

declare(strict_types=1);

namespace Core\Infrastructure\Repository;

use Core\Domain\Model\Item\Item;
use Core\Domain\Model\Item\ItemCollectionInterface;
use Core\Domain\Model\Item\ItemId;
use Prooph\EventSourcing\Aggregate\AggregateRepository;

final class EventStoreItemCollection extends AggregateRepository implements ItemCollectionInterface
{
    public function save(Item $item): void
    {
        $this->saveAggregateRoot($item);
    }

    public function get(ItemId $itemId): ?Item
    {
        return $this->getAggregateRoot((string) $itemId);
    }
}
