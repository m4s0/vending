<?php

declare(strict_types=1);

namespace Core\Application\Service;

use Common\Application\Service\BaseService;
use Core\Domain\Model\Coin\CoinId;
use Core\Domain\Model\Coin\View\Coin;
use Core\Domain\Model\Coin\View\CoinValue;
use Core\Domain\Model\Coin\View\CoinCollection;
use Core\Domain\Model\Coin\View\CoinResumeCollection;
use Core\Domain\Model\Coin\Type\CoinValueType;
use Core\Domain\Model\Coin\Query\GetCoinByValue;
use Core\Domain\Model\Coin\Command\InsertCoin;
use Core\Domain\Model\Coin\Command\ReturnCoin;
use Core\Domain\Model\Coin\Command\UpdateCoinAmount;
use Core\Domain\Model\Coin\Query\GetAllCoins;

final class CoinService extends BaseService
{
    public function load(): void
    {
        foreach (CoinValueType::INSERT as $value) {
            $payload = [
                'coin_id' => (string) CoinId::generate(),
                'value'   => (float) $value,
                'amount'  => 10
            ];

            $command = $this->messageFactory->createMessageFromArray(
                'Core\Domain\Model\Coin\Command\CreateCoin', ['payload' => $payload]
            );

            $this->commandBus->dispatch($command);
        }
    }

    public function findCoinByValue(CoinValue $value): ?Coin
    {
        $payload = [
            'value' => $value->value()
        ];

        $query = $this->messageFactory->createMessageFromArray(
            GetCoinByValue::class, ['payload' => $payload]
        );

        $coin = null;
        $this->queryBus->dispatch($query)->then(function ($result) use (&$coin) {
            $coin = $result;
        });

        if (!$coin) {
            throw new \InvalidArgumentException('Coin value not found: ' . $value);
        }

        return $coin;
    }

    public function insertCoin(Coin $coin): void
    {
        $payload = [
            'coin_id' => $coin->coinId()
        ];

        $command = $this->messageFactory->createMessageFromArray(
            InsertCoin::class, ['payload' => $payload]
        );

        $this->commandBus->dispatch($command);
    }

    public function insertCoins(CoinCollection $coins): void
    {
        foreach ($coins as $coin) {
            $this->insertCoin($coin);
        }
    }

    public function returnCoin(Coin $coin): ?Coin
    {
        $payload = [
            'coin_id' => $coin->coinId()
        ];

        $command = $this->messageFactory->createMessageFromArray(
            ReturnCoin::class, ['payload' => $payload]
        );

        $this->commandBus->dispatch($command);

        return $coin;
    }

    public function updateCoinAmount(Coin $coin, int $amount): void
    {
        $payload = [
            'coin_id' => $coin->coinId(),
            'amount' => $amount
        ];

        $command = $this->messageFactory->createMessageFromArray(
            UpdateCoinAmount::class, ['payload' => $payload]
        );

        $this->commandBus->dispatch($command);
    }

    public function returnCoins(CoinCollection $coins): ?CoinCollection
    {
        foreach ($coins as $coin) {
            $this->returnCoin($coin);
        }

        return $coins;
    }

    public function coins(): CoinResumeCollection
    {
        $payload = [];
        $query = $this->messageFactory->createMessageFromArray(
            GetAllCoins::class, ['payload' => $payload]
        );

        $coins = new CoinResumeCollection();
        $this->queryBus->dispatch($query)->then(function ($result) use (&$coins) {
            $coins = $result;
        });

        return $coins;
    }

    public function status(): array
    {
        $coins = $this->coins();

        return [
            'total' => $coins->total()->value(),
            'coins' => $coins->resume()
        ];
    }


}
