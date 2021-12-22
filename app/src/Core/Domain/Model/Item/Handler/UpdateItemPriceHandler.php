<?php

declare(strict_types=1);

namespace Core\Domain\Model\Item\Handler;

use Core\Domain\Model\Item\Item;
use Core\Domain\Model\Item\ItemCollectionInterface;
use Core\Domain\Model\Item\Command\UpdateItemPrice;

class UpdateItemPriceHandler
{
    /**
     * @var ItemCollectionInterface
     */
    private $itemCollection;

    public function __construct(
        ItemCollectionInterface $itemCollection
    ) {
        $this->itemCollection = $itemCollection;
    }

    public function __invoke(UpdateItemPrice $command): void
    {
        $item = $this->itemCollection->get($command->itemId());
        if (!$item) {
            throw new \InvalidArgumentException(\sprintf('Item with id %s cannot be found.', (string) $command->itemId()));
        }

        $item->updateItemPrice($command->price());
        $this->itemCollection->save($item);
    }
}
