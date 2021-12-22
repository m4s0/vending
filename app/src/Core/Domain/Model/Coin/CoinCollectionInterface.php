<?php

declare(strict_types=1);

namespace Core\Domain\Model\Coin;

interface CoinCollectionInterface
{
    public function save(Coin $coin): void;

    public function get(CoinId $coinId): ?Coin;
}
