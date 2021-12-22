<?php

declare(strict_types=1);

namespace Core\Domain\Model\Item;

use Assert\Assertion;
use Common\Domain\ValueObject\Number\BaseInteger;

final class ItemAmount extends BaseInteger
{
    public function validate(int $value): void
    {
        try {
            Assertion::min($value, 0);
            Assertion::max($value, 100);
        } catch (\Exception $e) {
            throw new \InvalidArgumentException('Invalid item amount because: '.$e->getMessage());
        }
    }

    public function dec(): self
    {
        if ($this->value() < 1) {
            throw new \InvalidArgumentException('Not enough amount from this item.');
        } else {
            return parent::dec();
        }
    }

}
