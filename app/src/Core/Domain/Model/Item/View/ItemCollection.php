<?php
// https://gist.github.com/eaglstun/1100119
declare(strict_types=1);

namespace Core\Domain\Model\Item\View;

class ItemCollection extends \ArrayObject implements \JsonSerializable
{
    public function offsetSet($offset, $value)
    {
        if (!$value instanceof Item) {
            throw new \Exception('value must be an instance of Item');
        }

        parent::offsetSet($offset, $value);
    }

    public function jsonSerialize()
    {
        return (array) $this;
    }
}
