<?php

declare(strict_types=1);

namespace Core\Domain\Model\Coin\Handler;

use Core\Domain\Model\Coin\Query\GetCoinByValue;
use Core\Infrastructure\Projection\Coin\CoinFinder;
use React\Promise\Deferred;

class GetCoinByValueHandler
{
    private $coinFinder;

    public function __construct(CoinFinder $coinFinder)
    {
        $this->coinFinder = $coinFinder;
    }

    public function __invoke(GetCoinByValue $query, Deferred $deferred = null)
    {
        $coin = $this->coinFinder->findByValue($query->value());
        if (!$coin) {
            throw new \InvalidArgumentException(\sprintf('We doesn\'t have coins ' . $query->value() . '.'));
        }

        if (null === $deferred) {
            return $coin;
        }

        $deferred->resolve($coin);
    }
}
