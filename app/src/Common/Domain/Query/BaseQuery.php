<?php

declare(strict_types=1);

namespace Common\Domain\Query;

use Prooph\Common\Messaging\Query as ProophQuery;

abstract class BaseQuery extends ProophQuery
{
    protected $payload;

    public function payload(): array
    {
        return $this->payload;
    }

    public function setPayload(array $payload): void
    {
        $this->payload = $payload;
    }
}
