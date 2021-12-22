<?php

declare(strict_types=1);

namespace Core\Domain\Model\Item\Handler;

use Core\Domain\Model\Item\Item;
use Core\Domain\Model\Item\ItemCollectionInterface;
use Core\Domain\Model\Item\Command\CreateItem;

class CreateItemHandler
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

    public function __invoke(CreateItem $command): void
    {
        if ($item = $this->itemCollection->get($command->itemId())) {
            throw new \InvalidArgumentException(\sprintf('Item with id %s already exists.', (string) $command->itemId()));
        }

        $item = Item::createWithData(
            $command->itemId(),
            $command->name(),
            $command->price(),
            $command->amount()
        );

        $this->itemCollection->save($item);
    }
}
