<?php

declare(strict_types=1);

namespace Core\Domain\Model\Coin\View;

use \ArrayObject;
use \JsonSerializable;

class CoinResumeCollection extends ArrayObject implements JsonSerializable
{
    public function offsetSet($offset, $value)
    {
        if (!$value instanceof CoinResume) {
            throw new \Exception('value must be an instance of CoinResume');
        }

        parent::offsetSet($offset, $value);
    }

    public function total(): MoneyValue
    {
        $coins = $this->getIterator();
        $total = MoneyValue::fromFloat(0);
        foreach ($coins as $coin) {
            $totalThisCoin = MoneyValue::fromFloat($coin->value()->value());
            $totalThisCoin = $totalThisCoin->mul(
                MoneyValue::fromFloat($coin->amount())
            );

            $total = $total->add($totalThisCoin);
        }

        return $total;
    }

    public function resume(): array
    {
        $data = json_decode(json_encode($this), true);
        array_walk($data, function (&$a) {
            unset($a['id']);
        });

        return $data;
    }

    public function jsonSerialize()
    {
        return (array) $this;
    }

}
