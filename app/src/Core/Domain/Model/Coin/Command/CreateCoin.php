<?php

declare(strict_types=1);

namespace Core\Domain\Model\Coin\Command;

use Assert\Assertion;
use Common\Domain\Command\BaseCommand;
use Core\Domain\Model\Coin\CoinAmount;
use Core\Domain\Model\Coin\CoinValue;
use Core\Domain\Model\Coin\CoinId;

final class CreateCoin extends BaseCommand
{
    public static function withData(string $coinId, float $value, int $amount): self
    {
        return new self([
            'coin_id' => $coinId,
            'value' => $value,
            'amount' => $amount
        ]);
    }

    public function coinId(): CoinId
    {
        return CoinId::fromString($this->payload['coin_id']);
    }

    public function value(): CoinValue
    {
        return CoinValue::fromFloat($this->payload['value']);
    }

    public function amount(): CoinAmount
    {
        return CoinAmount::fromInteger($this->payload['amount']);
    }

    public function validate(array $payload): void
    {
        Assertion::keyExists($payload, 'coin_id');
        Assertion::uuid($payload['coin_id']);
        Assertion::keyExists($payload, 'value');
        Assertion::float($payload['value']);
        Assertion::keyExists($payload, 'amount');
        Assertion::integer($payload['amount']);
    }
}
