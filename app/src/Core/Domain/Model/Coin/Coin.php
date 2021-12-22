<?php

declare(strict_types=1);

namespace Core\Domain\Model\Coin;

use Common\Domain\Aggregate\BaseAggregateRoot;
use Common\Domain\Entity\EntityInterface;
use Core\Domain\Model\Item\ItemId;
use Core\Domain\Model\Coin\Event\CoinWasCreated;
use Core\Domain\Model\Coin\Event\CoinWasInserted;
use Core\Domain\Model\Coin\Event\CoinWasReturned;
use Core\Domain\Model\Coin\Event\CoinAmountWasUpdated;

final class Coin extends BaseAggregateRoot
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

    public static function createWithData(
        CoinId $coinId,
        CoinValue $value,
        CoinAmount $amount
    ): Coin {
        $self = new self();

        $self->recordThat(CoinWasCreated::withData(
            $coinId,
            $value,
            $amount
        ));

        return $self;
    }

    public function insertCoin(): void
    {
        $amount = $this->amount->inc();
        $this->recordThat(CoinWasInserted::withData(
            $this->coinId,
            $this->value,
            $amount
        ));
    }

    public function returnCoin(): void
    {
        $amount = $this->amount->dec();
        $this->recordThat(CoinWasReturned::withData(
            $this->coinId,
            $this->value,
            $amount
        ));
    }

    public function updateCoinAmount(CoinAmount $amount): void
    {
        $this->recordThat(CoinAmountWasUpdated::withData(
            $this->coinId,
            $this->value,
            $this->amount,
            $amount
        ));
    }

    public function coinId(): CoinId
    {
        return $this->coinId;
    }

    public function value(): CoinValue
    {
        return $this->value;
    }

    public function amount(): CoinAmount
    {
        return $this->amount;
    }

    protected function aggregateId(): string
    {
        return (string) $this->coinId;
    }

    protected function whenCoinWasCreated(CoinWasCreated $event): void
    {
        $this->coinId = $event->coinId();
        $this->value = $event->value();
        $this->amount = $event->amount();
    }

    protected function whenCoinWasInserted(CoinWasInserted $event): void
    {
        $this->amount = $event->amount();
    }

    protected function whenCoinWasReturned(CoinWasReturned $event): void
    {
        $this->amount = $event->amount();
    }

    protected function whenCoinAmountWasUpdated(CoinAmountWasUpdated $event): void
    {
        $this->amount = $event->newAmount();
    }

    public function equals(EntityInterface $other): bool
    {
        return \get_class($this) === \get_class($other) && $this->coinId->equals($other->coinId);
    }
}
