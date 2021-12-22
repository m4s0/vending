<?php

declare(strict_types=1);

namespace Core\Domain\Model\Coin\Event;

use Common\Domain\Event\BaseAggregateChanged;
use Core\Domain\Model\Coin\CoinAmount;
use Core\Domain\Model\Coin\CoinValue;
use Core\Domain\Model\Coin\CoinId;

final class CoinWasCreated extends BaseAggregateChanged
{
    /**
     * @var CoinId
     */
    private $coinId;

    /**
     * @var CoinValue
     */
    private $value;

    /**
     * @var CoinAmount
     */
    private $amount;


    public static function withData(
        CoinId $coinId,
        CoinValue $value,
        CoinAmount $amount
    ): CoinWasCreated {
        /** @var self $event */
        $event = self::occur((string) $coinId, [
            'value' => $value->value(),
            'amount' => $amount->value()
        ]);

        $event->coinId = $coinId;
        $event->value = $value;
        $event->amount = $amount;

        return $event;
    }

    public function coinId(): CoinId
    {
        if (null === $this->coinId) {
            $this->coinId = CoinId::fromString($this->aggregateId());
        }

        return $this->coinId;
    }

    public function value(): CoinValue
    {
        if (null === $this->value) {
            $this->value = CoinValue::fromFloat($this->payload['value']);
        }

        return $this->value;
    }

    public function amount(): CoinAmount
    {
        if (null === $this->amount) {
            $this->amount = CoinAmount::fromInteger($this->payload['amount']);
        }

        return $this->amount;
    }

}
