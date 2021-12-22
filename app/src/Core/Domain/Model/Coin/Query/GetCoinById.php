<?php

declare(strict_types=1);

namespace Core\Domain\Model\Coin\Query;

use Common\Domain\Query\BaseQuery;

final class GetCoinById extends BaseQuery
{
    public function __construct(string $coinId)
    {
        $this->init();
        $this->setPayload([
            'coin_id' => $coinId,
        ]);
    }

    public static function withData(string $coinId): GetCoinById
    {
        return new self($coinId);
    }

    public function coinId(): string
    {
        return $this->payload['coin_id'];
    }
}
