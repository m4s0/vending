<?php

declare(strict_types=1);

namespace Core\Domain\Model\Coin\Type;

final class CoinValueType
{
    const INSERT = [
        0.05,
        0.10,
        0.25,
        1
    ];

    const RETURN = [
        0.05,
        0.10,
        0.25
    ];
}
