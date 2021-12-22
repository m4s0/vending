<?php

declare(strict_types=1);

namespace Core\Domain\Model\Coin\Handler;

use Core\Domain\Model\Coin\Query\GetCoinById;
use Core\Infrastructure\Projection\Coin\CoinFinder;
use React\Promise\Deferred;

class GetCoinByIdHandler
{
    private $coinFinder;

    public function __construct(CoinFinder $coinFinder)
    {
        $this->coinFinder = $coinFinder;
    }

    public function __invoke(GetCoinById $query, Deferred $deferred = null)
    {
        $coin = $this->coinFinder->findById($query->coinId());
        if (null === $deferred) {
            return $coin;
        }

        $deferred->resolve($coin);
    }
}
