<?php

declare(strict_types=1);

namespace Core\Application\Service;

use Common\Application\Service\BaseService;
use Core\Domain\Model\Item\ItemId;
use Core\Domain\Model\Item\View\Item;
use Core\Domain\Model\Item\View\ItemCollection;
use Core\Domain\Model\Item\Type\ItemPriceType;
use Core\Domain\Model\Item\Command\CreateItem;
use Core\Domain\Model\Item\Query\GetItemByName;
use Core\Domain\Model\Item\Command\BuyItem;
use Core\Domain\Model\Item\Command\UpdateItemAmount;
use Core\Domain\Model\Item\Command\UpdateItemPrice;
use Core\Domain\Model\Item\Query\GetAllItems;

final class ItemService extends BaseService
{

    public function load(): void
    {
        foreach (ItemPriceType::PRICES as $name => $price) {
            $payload = [
                'item_id' => (string) ItemId::generate(),
                'name'    => (string) $name,
                'price'   => (float) $price,
                'amount'  => (int) 10
            ];

            $command = $this->messageFactory->createMessageFromArray(
                CreateItem::class, ['payload' => $payload]
            );

            $this->commandBus->dispatch($command);
        }
    }

    public function findItemByName(string $name): ?Item
    {
        $payload = [
            'name' => $name
        ];

        $query = $this->messageFactory->createMessageFromArray(
            GetItemByName::class, ['payload' => $payload]
        );

        $item = null;
        $this->queryBus->dispatch($query)->then(function ($result) use (&$item) {
            $item = $result;
        });

        if (!$item) {
            throw new \InvalidArgumentException('Item not found: '. $name);
        }

        return $item;
    }

    public function buyItem(Item $item): string
    {
        $payload = [
            'item_id' => $item->itemId()
        ];

        $command = $this->messageFactory->createMessageFromArray(
            BuyItem::class, ['payload' => $payload]
        );

        $this->commandBus->dispatch($command);

        return $item->name();
    }

    public function updateItemAmount(Item $item, int $amount): void
    {
        $payload = [
            'item_id' => $item->itemId(),
            'amount' => $amount
        ];

        $command = $this->messageFactory->createMessageFromArray(
            UpdateItemAmount::class, ['payload' => $payload]
        );

        $this->commandBus->dispatch($command);
    }

    public function updateItemPrice(Item $item, float $price): void
    {
        $payload = [
            'item_id' => $item->itemId(),
            'price' => $price
        ];

        $command = $this->messageFactory->createMessageFromArray(
            UpdateItemPrice::class, ['payload' => $payload]
        );

        $this->commandBus->dispatch($command);
    }

    public function items(): ItemCollection
    {
        $payload = [];
        $query = $this->messageFactory->createMessageFromArray(
            GetAllItems::class, ['payload' => $payload]
        );

        $items = new ItemCollection();
        $this->queryBus->dispatch($query)->then(function ($result) use (&$items) {
            $items = $result;
        });

        return $items;
    }

    public function status(): array
    {
        $items = $this->items();
        $status = [];
        foreach ($items as $item) {
            $status[] = [
                'item'   => $item->name(),
                'price'  => $item->price()->value(),
                'amount' => $item->amount()
            ];
        }
        return $status;
    }

}
