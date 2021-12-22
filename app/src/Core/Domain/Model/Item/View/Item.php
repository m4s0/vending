<?php

declare(strict_types=1);

namespace Core\Domain\Model\Item\View;

use \JsonSerializable;
use Core\Domain\Model\Item\View\ItemPrice;

final class Item implements JsonSerializable
{
    /**
     * @var ItemId
     */
    private $id;

    private $name;

    private $price;

    private $amount;

    public function __construct()
    {
        $this->id     = (string) $this->id;
        $this->name   = (string) $this->name;
        $this->price  = ItemPrice::fromString($this->price);
        $this->amount = (int) $this->amount;
    }

    public function itemId(): string
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function price(): ItemPrice
    {
        return $this->price;
    }

    public function amount(): int
    {
        return $this->amount;
    }

    public function toArray(): array
    {
        return [
            'id'     => $this->id,
            'name'   => $this->name,
            'price'  => $this->price->value(),
            'amount' => $this->amount
        ];
    }

    public function jsonSerialize()
    {
        return $this->toArray();
    }
}
