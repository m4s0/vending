<?php

declare(strict_types=1);

namespace Core\Domain\Model\Item\Event;

use Common\Domain\Event\BaseAggregateChanged;
use Core\Domain\Model\Item\ItemId;
use Core\Domain\Model\Item\ItemName;
use Core\Domain\Model\Item\ItemPrice;
use Core\Domain\Model\Item\ItemAmount;

final class ItemPriceWasUpdated extends BaseAggregateChanged
{
    /**
     * @var ItemId
     */
    private $itemId;

    /**
     * @var ItemName
     */
    private $name;

    /**
     * @var ItemPrice
     */
    private $oldPrice;

    /**
     * @var ItemPrice
     */
    private $newPrice;

    /**
     * @var ItemAmount
     */
    private $amount;

    public static function withData(
        ItemId $itemId,
        ItemName $name,
        ItemPrice $oldPrice,
        ItemPrice $newPrice,
        ItemAmount $amount
    ): ItemPriceWasUpdated {
        /** @var self $event */
        $event = self::occur((string) $itemId, [
            'name' => $name->value(),
            'old_price' => $oldPrice->value(),
            'new_price' => $newPrice->value(),
            'amount' => $amount->value()
        ]);

        $event->itemId = $itemId;
        $event->name = $name;
        $event->oldPrice = $oldPrice;
        $event->newPrice = $newPrice;
        $event->amount = $amount;

        return $event;
    }

    public function itemId(): ItemId
    {
        if (null === $this->itemId) {
            $this->itemId = ItemId::fromString($this->aggregateId());
        }

        return $this->itemId;
    }

    public function name(): ItemName
    {
        if (null === $this->name) {
            $this->name = ItemName::fromString($this->payload['name']);
        }

        return $this->name;
    }

    public function oldPrice(): ItemPrice
    {
        if (null === $this->oldPrice) {
            $this->oldPrice = ItemPrice::fromFloat($this->payload['old_price']);
        }

        return $this->oldPrice;
    }

    public function newPrice(): ItemPrice
    {
        if (null === $this->newPrice) {
            $this->newPrice = ItemPrice::fromFloat($this->payload['new_price']);
        }

        return $this->newPrice;
    }

    public function amount(): ItemAmount
    {
        if (null === $this->amount) {
            $this->amount = ItemAmount::fromInteger($this->payload['amount']);
        }

        return $this->amount;
    }

}
