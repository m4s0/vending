<?php

declare(strict_types=1);

namespace Core\Domain\Model\Coin\Query;

use Common\Domain\Query\BaseQuery;

final class GetCoinByValue extends BaseQuery
{
    public function __construct(float $value)
    {
        $this->init();
        $this->setPayload([
            'value' => $value,
        ]);
    }

    public static function withData(float $value): GetCoinByValue
    {
        return new self($value);
    }

    public function value(): float
    {
        return $this->payload['value'];
    }
}
