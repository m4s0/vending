<?php

declare(strict_types=1);

namespace Core\Infrastructure\Projection\Item;

use Common\Infrastructure\Projection\BaseReadProjection;
use Core\Domain\Model\Item\Event\ItemWasCreated;
use Core\Domain\Model\Item\Event\ItemWasBought;
use Core\Domain\Model\Item\Event\ItemAmountWasUpdated;
use Core\Domain\Model\Item\Event\ItemPriceWasUpdated;

final class ItemProjection extends BaseReadProjection
{
    public function listenerList(): array
    {
        return [
            ItemWasCreated::class => function ($state, ItemWasCreated $event) {
                /** @var ItemReadModel $readModel */
                // @phpstan-ignore-next-line
                $readModel = $this->readModel();
                $readModel->stack('insert', [
                    'id' => $event->itemId()->value(),
                    'name' => $event->name()->value(),
                    'price' => $event->price()->value(),
                    'amount' => $event->amount()->value()
                ]);
            },
            ItemWasBought::class => function ($state, ItemWasBought $event) {
                /** @var ItemReadModel $readModel */
                // @phpstan-ignore-next-line
                $readModel = $this->readModel();
                $readModel->stack('update', [
                    'amount' => $event->amount()->value()
                ],[
                    'id' => $event->itemId()->value()
                ]);
            },
            ItemAmountWasUpdated::class => function ($state, ItemAmountWasUpdated $event) {
                /** @var ItemReadModel $readModel */
                // @phpstan-ignore-next-line
                $readModel = $this->readModel();
                $readModel->stack('update', [
                    'amount' => $event->newAmount()->value()
                ],[
                    'id' => $event->itemId()->value()
                ]);
            },
            ItemPriceWasUpdated::class => function ($state, ItemPriceWasUpdated $event) {
                /** @var ItemReadModel $readModel */
                // @phpstan-ignore-next-line
                $readModel = $this->readModel();
                $readModel->stack('update', [
                    'price' => $event->newPrice()->value()
                ],[
                    'id' => $event->itemId()->value()
                ]);
            },
        ];
    }
}
