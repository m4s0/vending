<?php

declare(strict_types=1);

namespace Core\Domain\Model\Coin\View;

use \JsonSerializable;
use Core\Domain\Model\Coin\View\CoinValue;

final class Coin implements JsonSerializable
{
    /**
     * @var CoinId
     */
    private $id;

    private $value;

    // Mandatory PDO fetch class, property types
    public function __construct()
    {
        if (!empty($this->value)) {
            $this->id     = (string) $this->id;
            $this->value  = CoinValue::fromString($this->value);
        }
    }

    public static function createWithData(
        string $coinId,
        CoinValue $value
    ): Coin {

        $self        = new self();
        $self->id    = $coinId;
        $self->value = $value;

        return $self;
    }

    public function coinId(): string
    {
        return $this->id;
    }

    public function value(): CoinValue
    {
        return $this->value;
    }

    public function toArray(): array
    {
        return [
            'id'     => $this->id,
            'value'  => $this->value->value()
        ];
    }

    public function jsonSerialize()
    {
        return $this->toArray();
    }
}
