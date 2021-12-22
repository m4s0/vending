<?php

declare(strict_types=1);

namespace API\Service;

use API\Service\PocketService;
use Core\Application\Service\CoinService;
use Core\Application\Service\ItemService;
use Core\Domain\Model\Coin\View\ChangeValue;
use Core\Domain\Model\Coin\View\CoinValue;
use Core\Domain\Model\Item\View\ItemPrice;
use Core\Application\Calculate\Change;

use Core\Domain\Model\Coin\View\CoinCollection;

final class VendingService
{
    private $pocketService;
    private $coinService;
    private $itemService;

    public function __construct(
        PocketService $pocketService,
        CoinService $coinService,
        ItemService $itemService
    ) {
        $this->pocketService = $pocketService;
        $this->coinService = $coinService;
        $this->itemService = $itemService;
    }

    public function insertCoin(string $coinString): void
    {
        $coinValue = CoinValue::fromString($coinString);
        $coin = $this->coinService->findCoinByValue($coinValue);
        $this->pocketService->insertCoin($coin);
    }

    public function returnAllCoins(): array
    {
        $coinsToReturn = $this->pocketService->coins();
        $coinsReturned = $this->pocketService->returnCoins($coinsToReturn);
        $this->pocketService->reset();

        return $coinsReturned->values();
    }

    public function coinStatus(): array
    {
        return $this->pocketService->status();
    }

    public function buyItem(string $itemName): array
    {
        $item  = $this->itemService->findItemByName($itemName);
        if ($item->amount() < 1) {
            throw new \InvalidArgumentException('We don\'t have enough ' . $itemName);
        }

        $change = new Change(
            $item->price(),
            $this->pocketService->coins(),
            $this->coinService->coins()
        );

        # return all change coins
        $changeCoinsFromPocket = $this->pocketService->returnCoins(
                $change->changeCoinsFromPocket()
        );
        $changeCoinsFromMachine = $this->coinService->returnCoins(
                $change->changeCoinsFromMachine()
        );

        # the coins on the pocket are the price of the item
        $this->coinService->insertCoins(
            $this->pocketService->coins()
        );

        $itemName = $this->itemService->buyItem($item);
        $this->pocketService->reset();

        return array_merge([$itemName], $changeCoinsFromPocket->values(), $changeCoinsFromMachine->values());
    }

    public function itemStatus(): array
    {
        return $this->itemService->status();
    }

    public function serviceItemUpdate(string $itemName, array $payload): array
    {
        $item  = $this->itemService->findItemByName($itemName);
        if (isset($payload['price'])) {
            $this->itemService->updateItemPrice($item, (float) $payload['price']);
        }
        if (isset($payload['amount'])) {
            $this->itemService->updateItemAmount($item, (int) $payload['amount']);
        }

        return $payload;
    }

    public function serviceCoinUpdate(string $coinString, array $payload): array
    {
        $coinValue = CoinValue::fromString($coinString);
        $coin = $this->coinService->findCoinByValue($coinValue);
        if (isset($payload['amount'])) {
            $this->coinService->updateCoinAmount($coin, (int) $payload['amount']);
        }

        return $payload;
    }

    public function serviceStatus(): array
    {
        $status = [
            'pocket'  => $this->pocketService->status(),
            'machine' => $this->coinService->status(),
            'items'   => $this->itemService->status()
        ];

        return $status;
    }
}
