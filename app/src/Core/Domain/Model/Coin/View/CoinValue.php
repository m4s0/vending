<?php

declare(strict_types=1);

namespace Core\Domain\Model\Coin\View;

use Assert\Assertion;
use Common\Domain\ValueObject\Money\BaseMoney;
use Core\Domain\Model\Coin\Type\CoinValueType;

final class CoinValue extends BaseMoney
{
    public function validate(float $value): void
    {
        try {
            Assertion::true(in_array($value, CoinValueType::INSERT));
        } catch (\Exception $e) {
            throw new \InvalidArgumentException('Invalid coin value because ' . $value . ' is not one of ' . implode(", ", CoinValueType::INSERT));
        }
    }
}
