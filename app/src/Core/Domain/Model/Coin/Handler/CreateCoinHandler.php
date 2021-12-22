<?php

declare(strict_types=1);

namespace Core\Domain\Model\Coin\Handler;

use Core\Domain\Model\Item\ItemCollectionInterface;
use Core\Domain\Model\Coin\Command\CreateCoin;
use Core\Domain\Model\Coin\Coin;
use Core\Domain\Model\Coin\CoinCollectionInterface;

class CreateCoinHandler
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
    public function __invoke(CreateCoin $command): void
    {
        if ($coin = $this->coinCollection->get($command->coinId())) {
            throw new \InvalidArgumentException(\sprintf('Coin with id %s already exists.', (string) $command->coinId()));
        }

        $coin = Coin::createWithData(
            $command->coinId(),
            $command->value(),
            $command->amount()
        );

        $this->coinCollection->save($coin);
    }
}
