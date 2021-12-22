<?php

declare(strict_types=1);

namespace Core\Domain\Model\Item\Handler;

use Core\Domain\Model\Item\Query\GetAllItems;
use Core\Infrastructure\Projection\Item\ItemFinder;
use React\Promise\Deferred;

class GetAllItemsHandler
{
    private $itemFinder;

    public function __construct(ItemFinder $itemFinder)
    {
        $this->itemFinder = $itemFinder;
    }

    public function __invoke(GetAllItems $query, Deferred $deferred = null)
    {
        $items = $this->itemFinder->findAll();
        if (null === $deferred) {
            return $items;
        }

        $deferred->resolve($items);
    }
}
