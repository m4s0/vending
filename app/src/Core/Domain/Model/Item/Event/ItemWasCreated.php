<?php

declare(strict_types=1);

namespace Core\Domain\Model\Item\Event;

use Common\Domain\Event\BaseAggregateChanged;
use Core\Domain\Model\Item\ItemId;
use Core\Domain\Model\Item\ItemName;
use Core\Domain\Model\Item\ItemPrice;
use Core\Domain\Model\Item\ItemAmount;

final class ItemWasCreated extends BaseAggregateChanged
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
    private $amount;

    public static function withData(
        ItemId $itemId,
        ItemName $name,
        ItemPrice $price,
        ItemAmount $amount
    ): ItemWasCreated {
        /** @var self $event */
        $event = self::occur((string) $itemId, [
            'name' => $name->value(),
            'price' => $price->value(),
            'amount' => $amount->value()
        ]);

        $event->itemId = $itemId;
        $event->name = $name;
        $event->price = $price;
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

    public function price(): ItemPrice
    {
        if (null === $this->price) {
            $this->price = ItemPrice::fromFloat($this->payload['price']);
        }

        return $this->price;
    }

    public function amount(): ItemAmount
    {
        if (null === $this->amount) {
            $this->amount = ItemAmount::fromInteger($this->payload['amount']);
        }

        return $this->amount;
    }

}
