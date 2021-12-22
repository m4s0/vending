<?php

declare(strict_types=1);

namespace Core\Domain\Model\Coin\Event;

use Common\Domain\Event\BaseAggregateChanged;
use Core\Domain\Model\Coin\CoinAmount;
use Core\Domain\Model\Coin\CoinValue;
use Core\Domain\Model\Coin\CoinId;

final class CoinAmountWasUpdated extends BaseAggregateChanged
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
    private $oldAmount;

    /**
     * @var CoinAmount
     */
    private $newAmount;


    public static function withData(
        CoinId $coinId,
        CoinValue $value,
        CoinAmount $oldAmount,
        CoinAmount $newAmount
    ): CoinAmountWasUpdated {
        /** @var self $event */
        $event = self::occur((string) $coinId, [
            'value' => $value->value(),
            'old_amount' => $oldAmount->value(),
            'new_amount' => $newAmount->value()
        ]);

        $event->coinId = $coinId;
        $event->value = $value;
        $event->oldAmount = $oldAmount;
        $event->newAmount = $newAmount;

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

    public function oldAmount(): CoinAmount
    {
        if (null === $this->oldAmount) {
            $this->oldAmount = CoinAmount::fromInteger($this->payload['old_amount']);
        }

        return $this->oldAmount;
    }

    public function newAmount(): CoinAmount
    {
        if (null === $this->newAmount) {
            $this->newAmount = CoinAmount::fromInteger($this->payload['new_amount']);
        }

        return $this->newAmount;
    }


}
