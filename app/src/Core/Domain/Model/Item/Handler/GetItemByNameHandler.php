<?php

declare(strict_types=1);

namespace Core\Domain\Model\Item\Handler;

use Core\Domain\Model\Item\Query\GetItemByName;
use Core\Infrastructure\Projection\Item\ItemFinder;
use React\Promise\Deferred;

class GetItemByNameHandler
{
    private $itemFinder;

    public function __construct(ItemFinder $itemFinder)
    {
        $this->itemFinder = $itemFinder;
    }

    public function __invoke(GetItemByName $query, Deferred $deferred = null)
    {
        $item = $this->itemFinder->findByName($query->name());
        if (!$item) {
            throw new \InvalidArgumentException(\sprintf('We doesn\'t have ' . $query->name() . '.'));
        }

        if (null === $deferred) {
            return $item;
        }

        $deferred->resolve($item);
    }
}
