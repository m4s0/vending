<?php

declare(strict_types=1);

namespace Core\Domain\Model\Coin\Command;

use Assert\Assertion;
use Common\Domain\Command\BaseCommand;
use Core\Domain\Model\Coin\CoinId;

final class ReturnCoin extends BaseCommand
{
    public static function withData(string $coinId): self
    {
        return new self([
            'coin_id' => $coinId
        ]);
    }

    public function coinId(): CoinId
    {
        return CoinId::fromString($this->payload['coin_id']);
    }

    public function validate(array $payload): void
    {
        Assertion::keyExists($payload, 'coin_id');
        Assertion::uuid($payload['coin_id']);
    }
}
