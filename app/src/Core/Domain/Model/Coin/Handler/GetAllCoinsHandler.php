<?php

declare(strict_types=1);

namespace Core\Domain\Model\Coin\Handler;

use Core\Domain\Model\Coin\Query\GetAllCoins;
use Core\Infrastructure\Projection\Coin\CoinFinder;
use Core\Infrastructure\Projection\Coin\CoinResumeFinder;
use React\Promise\Deferred;

class GetAllCoinsHandler
{
    private $coinFinder;
    private $coinResumeFinder;

    public function __construct(
        CoinFinder $coinFinder,
        CoinResumeFinder $coinResumeFinder
    )
    {
        $this->coinFinder = $coinFinder;
        $this->coinResumeFinder = $coinResumeFinder;
    }

    public function __invoke(GetAllCoins $query, Deferred $deferred = null)
    {
        $coins = $this->coinResumeFinder->findAll();
        if (null === $deferred) {
            return $coins;
        }

        $deferred->resolve($coins);
    }
}
