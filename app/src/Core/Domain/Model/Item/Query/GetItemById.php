<?php

declare(strict_types=1);

namespace Core\Domain\Model\Item\Query;

use Common\Domain\Query\BaseQuery;

final class GetItemById extends BaseQuery
{
    public function __construct(string $itemId)
    {
        $this->init();
        $this->setPayload([
            'item_id' => $itemId,
        ]);
    }

    public static function withData(string $itemId): GetItemById
    {
        return new self($itemId);
    }

    public function itemId(): string
    {
        return $this->payload['item_id'];
    }
}
