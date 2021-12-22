<?php

declare(strict_types=1);

namespace Core\Infrastructure\Projection\Coin;

use Common\Infrastructure\Projection\BaseReadProjection;
use Core\Domain\Model\Coin\Event\CoinWasCreated;
use Core\Domain\Model\Coin\Event\CoinWasInserted;
use Core\Domain\Model\Coin\Event\CoinWasReturned;
use Core\Domain\Model\Coin\Event\CoinAmountWasUpdated;

final class CoinProjection extends BaseReadProjection
{
    public function listenerList(): array
    {
        return [
            CoinWasCreated::class => function ($state, CoinWasCreated $event) {
                /** @var CoinReadModel $readModel */
                // @phpstan-ignore-next-line
                $readModel = $this->readModel();
                $readModel->stack('insert', [
                    'id' => $event->coinId()->value(),
                    'value' => $event->value()->value(),
                    'amount' => $event->amount()->value()
                ]);
            },
            CoinWasInserted::class => function ($state, CoinWasInserted $event) {
                /** @var CoinReadModel $readModel */
                // @phpstan-ignore-next-line
                $readModel = $this->readModel();
                $readModel->stack('update', [
                    'amount' => $event->amount()->value()
                ],[
                    'id' => $event->coinId()->value()
                ]);
            },
            CoinWasReturned::class => function ($state, CoinWasReturned $event) {
                /** @var CoinReadModel $readModel */
                // @phpstan-ignore-next-line
                $readModel = $this->readModel();
                $readModel->stack('update', [
                    'amount' => $event->amount()->value()
                ],[
                    'id' => $event->coinId()->value()
                ]);
            },
            CoinAmountWasUpdated::class => function ($state, CoinAmountWasUpdated $event) {
                /** @var CoinReadModel $readModel */
                // @phpstan-ignore-next-line
                $readModel = $this->readModel();
                $readModel->stack('update', [
                    'amount' => $event->newAmount()->value()
                ],[
                    'id' => $event->coinId()->value()
                ]);
            },
        ];
    }
}
