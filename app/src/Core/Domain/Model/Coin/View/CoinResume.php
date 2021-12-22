<?php

declare(strict_types=1);

namespace Core\Domain\Model\Coin\View;

use Core\Domain\Model\Coin\View\Coin;
use Core\Domain\Model\Coin\View\CoinValue;
use \JsonSerializable;

final class CoinResume implements JsonSerializable
{
    /**
     * @var CoinId
     */
    private $id;

    private $value;

    private $amount;

    // Mandatory PDO fetch class, property types
    public function __construct()
    {
        if (!empty($this->value)) {
            $this->id     = (string) $this->id;
            $this->value  = CoinValue::fromString($this->value);
            $this->amount = (int) $this->amount;
        }
    }

    public static function createWithData(
        string $coinId,
        CoinValue $value,
        int $amount
    ): Coin {

        $self         = new self();
        $self->id     = $coinId;
        $self->value  = $value;
        $self->amount = $amount;

        return $self;
    }

    public function coin(): Coin
    {
        return Coin::createWithData(
            $this->id,
            $this->value
        );
    }

    public function coinId(): string
    {
        return $this->id;
    }

    public function value(): CoinValue
    {
        return $this->value;
    }

    public function amount(): int
    {
        return $this->amount;
    }

    public function toArray(): array
    {
        return [
            'id'     => $this->id,
            'value'  => $this->value->value(),
            'amount' => $this->amount
        ];
    }

    public function jsonSerialize()
    {
        return $this->toArray();
    }
}
