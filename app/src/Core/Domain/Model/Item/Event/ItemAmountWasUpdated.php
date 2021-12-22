<?php

declare(strict_types=1);

namespace Core\Domain\Model\Item\Event;

use Common\Domain\Event\BaseAggregateChanged;
use Core\Domain\Model\Item\ItemId;
use Core\Domain\Model\Item\ItemName;
use Core\Domain\Model\Item\ItemPrice;
use Core\Domain\Model\Item\ItemAmount;

final class ItemAmountWasUpdated extends BaseAggregateChanged
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
    private $price;

    /**
     * @var ItemAmount
     */
    private $oldAmount;

    /**
     * @var ItemAmount
     */
    private $newAmount;

    public static function withData(
        ItemId $itemId,
        ItemName $name,
        ItemPrice $price,
        ItemAmount $oldAmount,
        ItemAmount $newAmount
    ): ItemAmountWasUpdated {
        /** @var self $event */
        $event = self::occur((string) $itemId, [
            'name' => $name->value(),
            'price' => $price->value(),
            'old_amount' => $oldAmount->value(),
            'new_amount' => $newAmount->value()
        ]);

        $event->itemId = $itemId;
        $event->name = $name;
        $event->price = $price;
        $event->oldAmount = $oldAmount;
        $event->newAmount = $newAmount;

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

    public function price(): ItemPrice
    {
        if (null === $this->price) {
            $this->price = ItemPrice::fromFloat($this->payload['price']);
        }

        return $this->price;
    }

    public function oldAmount(): ItemAmount
    {
        if (null === $this->oldAmount) {
            $this->oldAmount = ItemAmount::fromInteger($this->payload['old_amount']);
        }

        return $this->oldAmount;
    }

    public function newAmount(): ItemAmount
    {
        if (null === $this->newAmount) {
            $this->newAmount = ItemAmount::fromInteger($this->payload['new_amount']);
        }

        return $this->newAmount;
    }

}
