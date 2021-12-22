<?php

declare(strict_types=1);

namespace Core\Infrastructure\Repository;

use Core\Domain\Model\Coin\Coin;
use Core\Domain\Model\Coin\CoinCollectionInterface;
use Core\Domain\Model\Coin\CoinId;
use Prooph\EventSourcing\Aggregate\AggregateRepository;

final class EventStoreCoinCollection extends AggregateRepository implements CoinCollectionInterface
{
    public function save(Coin $coin): void
    {
        $this->saveAggregateRoot($coin);
    }

    public function get(CoinId $coinId): ?Coin
    {
        return $this->getAggregateRoot((string) $coinId);
    }
}
