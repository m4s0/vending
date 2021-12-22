<?php

declare(strict_types=1);

namespace Core\Domain\Model\Coin\Command;

use Assert\Assertion;
use Common\Domain\Command\BaseCommand;
use Core\Domain\Model\Coin\CoinAmount;
use Core\Domain\Model\Coin\CoinValue;
use Core\Domain\Model\Coin\CoinId;

final class UpdateCoinAmount extends BaseCommand
{
    public static function withData(string $coinId, int $amount): self
    {
        return new self([
            'coin_id' => $coinId,
            'amount' => $amount
        ]);
    }

    public function coinId(): CoinId
    {
        return CoinId::fromString($this->payload['coin_id']);
    }

    public function amount(): CoinAmount
    {
        return CoinAmount::fromInteger($this->payload['amount']);
    }

    public function validate(array $payload): void
    {
        Assertion::keyExists($payload, 'coin_id');
        Assertion::uuid($payload['coin_id']);
        Assertion::keyExists($payload, 'amount');
        Assertion::integer($payload['amount']);
    }
}
