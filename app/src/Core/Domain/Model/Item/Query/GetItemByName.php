<?php

declare(strict_types=1);

namespace Core\Domain\Model\Item\Query;

use Common\Domain\Query\BaseQuery;

final class GetItemByName extends BaseQuery
{
    public function __construct(string $name)
    {
        $this->init();
        $this->setPayload([
            'name' => $name,
        ]);
    }

    public static function withData(string $name): GetItemByName
    {
        return new self($name);
    }

    public function name(): string
    {
        return $this->payload['name'];
    }
}
