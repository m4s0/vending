<?php

declare(strict_types=1);

namespace Core\Domain\Model\Item\View;

use Assert\Assertion;
use Common\Domain\ValueObject\Money\BaseMoney;

final class ItemPrice extends BaseMoney
{
    public function validate(float $value): void
    {
        try {
            Assertion::min($value, 0);
            Assertion::max($value, 9.99);
        } catch (\Exception $e) {
            throw new \InvalidArgumentException('Invalid item price because: '.$e->getMessage());
        }
    }
}
