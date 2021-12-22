<?php

declare(strict_types=1);

namespace Core\Domain\Model\Item;

use Assert\Assertion;
use Common\Domain\ValueObject\String\BaseString;
use Core\Domain\Model\Item\Type\ItemPriceType;

final class ItemName extends BaseString
{
    public function validate(string $value): void
    {
        try {
            Assertion::inArray($value, array_keys(ItemPriceType::PRICES));
        } catch (\Exception $e) {
            throw new \InvalidArgumentException('Invalid item name because: '.$e->getMessage());
        }
    }
}
