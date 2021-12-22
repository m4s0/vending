<?php

declare(strict_types=1);

namespace Core\Domain\Model\Item\Handler;

use Core\Domain\Model\Item\Query\GetItemById;
use Core\Infrastructure\Projection\Item\ItemFinder;
use React\Promise\Deferred;

class GetItemByIdHandler
{
    private $itemFinder;

    public function __construct(ItemFinder $itemFinder)
    {
        $this->itemFinder = $itemFinder;
    }

    public function __invoke(GetItemById $query, Deferred $deferred = null)
    {
        $item = $this->itemFinder->findById($query->itemId());
        if (null === $deferred) {
            return $item;
        }

        $deferred->resolve($item);
    }
}
