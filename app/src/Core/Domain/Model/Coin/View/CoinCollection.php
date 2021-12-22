<?php
// https://dev.to/drearytown/collection-objects-in-php-1cbk
declare(strict_types=1);

namespace Core\Domain\Model\Coin\View;

use \ArrayObject;
use \JsonSerializable;

class CoinCollection extends ArrayObject implements JsonSerializable
{
    public function __construct(array $values = [])
    {
        foreach ($values as $value) {
            $this->offsetSet(null, $value);
        }
    }

    public function offsetSet($offset, $value)
    {
        if (!$value instanceof Coin) {
            throw new \Exception('value must be an instance of Coin');
        }

        parent::offsetSet($offset, $value);
    }

    public function contains(Coin $coin): bool
    {
        $data = json_decode(json_encode($this), true);
        $ids  = array_column($data, 'id');
        return in_array($coin->coinId(), $ids);
    }

    public function remove(Coin $coin): void
    {
        $data = json_decode(json_encode($this), true);
        $ids  = array_column($data, 'id');
        if (($key = array_search($coin->coinId(), $ids, true)) !== FALSE) {
            $this->offsetUnset($key);
        }
    }

    public function total(): MoneyValue
    {
        $coins = $this->getIterator();
        $total = MoneyValue::fromFloat(0);
        foreach ($coins as $coin) {
            $total = $total->add($coin->value());
        }

        return $total;
    }

    public function sortByValuesDesc(): void
    {
        $this->uasort(function ($a, $b) {
            $a = $a->value();
            $b = $b->value();

            if ($a->equals($b)) {
                return 0;
            }
            return ($a->isGreaterThan($b)) ? -1 : 1;
        });
    }

    public function values(): array
    {
        $data = json_decode(json_encode($this), true);
        $values  = array_column($data, 'value');

        return $values;
    }

    public function jsonSerialize()
    {
        return (array) $this;
    }
}
