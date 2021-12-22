<?php

declare(strict_types=1);

namespace Core\Domain\Model\Coin\Handler;

use Core\Domain\Model\Coin\Command\InsertCoin;
use Core\Domain\Model\Coin\Coin;
use Core\Domain\Model\Coin\CoinCollectionInterface;

class InsertCoinHandler
{
    /**
     * @var CoinCollectionInterface
     */
    private $coinCollection;

    public function __construct(CoinCollectionInterface $coinCollection)
    {
        $this->coinCollection = $coinCollection;
    }

    /**
     * @throws ItemNotFound
     */
    public function __invoke(InsertCoin $command): void
    {
        $coin = $this->coinCollection->get($command->coinId());
        if (!$coin) {
            throw new \InvalidArgumentException(\sprintf('Coin with id %s cannot be found.', (string) $command->coinId()));
        }

        $coin->insertCoin();
        $this->coinCollection->save($coin);
    }
}
