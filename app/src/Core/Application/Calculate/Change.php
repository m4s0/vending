<?php

declare(strict_types=1);

namespace Core\Application\Calculate;

use Core\Domain\Model\Coin\View\CoinValue;
use Core\Domain\Model\Coin\View\ChangeValue;
use Core\Domain\Model\Coin\View\CoinCollection;
use Core\Domain\Model\Coin\View\CoinResumeCollection;
use Core\Domain\Model\Item\View\ItemPrice;
use Core\Domain\Model\Coin\Type\CoinValueType;

final class Change
{
    private $change;
    private $price;
    private $coinsPocket;
    private $coinsResumeMachine;
    private $changeCoinsFromPocket;
    private $changeCoinsFromMachine;

    public function __construct(
        ItemPrice $price,
        CoinCollection $coinsPocket,
        CoinResumeCollection $coinsResumeMachine
    )
    {
        $this->price              = $price;
        $this->coinsPocket        = $coinsPocket;
        $this->coinsResumeMachine = $coinsResumeMachine;

        $change = ChangeValue::fromBase($this->coinsPocket->total());
        $change = $change->sub($price);

        if ($change->isLessThanZero()) {
            throw new \InvalidArgumentException('Not enough money for it, that cost ' . $price);
        }

        $this->changeCoinsFromPocket = $this->availableCoinsFromPocket($change);
        $change = $change->sub($this->changeCoinsFromPocket->total());
        $this->changeCoinsFromMachine = $this->availableCoinsFromMachine($change);
        $change = $change->sub($this->changeCoinsFromMachine->total());

        if ($change->isGreaterThanZero()) {
            throw new \InvalidArgumentException('We don\'t have enough coins to return the change.');
        }

        $this->change = $change;
    }


    private function availableCoinsFromPocket(ChangeValue $change): CoinCollection
    {
        $availableCoins = new CoinCollection();
        if ($change->isGreaterThanZero()) {
            $pocket = $this->coinsPocket;
            $pocket->sortByValuesDesc();
            foreach ($pocket->getIterator() as $coin) {
                $value  = $coin->value();
                if (in_array($value->value(), CoinValueType::RETURN)) {
                    if ($change->isGreaterThanOrEqualTo($value)) {
                        $availableCoins->append($coin);
                        $change = $change->sub($value);
                    }
                }
            }
        }

        return $availableCoins;
    }

    private function availableCoinsFromMachine(ChangeValue $change): CoinCollection
    {
        $availableCoins = new CoinCollection();
        if ($change->isGreaterThanZero()) {
            $coins = $this->coinsResumeMachine;
            foreach ($coins as $coin) {
                $value  = $coin->value();
                if (in_array($value->value(), CoinValueType::RETURN)) {
                    $amount = $coin->amount();
                    while ($change->isGreaterThanOrEqualTo($value) && ($amount > 0)) {
                        $availableCoins->append($coin->coin());
                        $change = $change->sub($value);
                        $amount--;
                    }
                }
            }
        }

        return $availableCoins;
    }

    public function price(): ItemPrice
    {
        return $this->price;
    }

    public function change(): ChangeValue
    {
        return $this->change;
    }

    public function changeCoinsFromPocket(): CoinCollection
    {
        return $this->changeCoinsFromPocket;
    }

    public function changeCoinsFromMachine(): CoinCollection
    {
        return $this->changeCoinsFromMachine;
    }

}
