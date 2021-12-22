<?php

declare(strict_types=1);

namespace Common\Domain\Command;

use Prooph\Common\Messaging\Command as ProophCommand;
use Prooph\Common\Messaging\PayloadConstructable as ProophPayloadConstructable;
use Prooph\Common\Messaging\PayloadTrait as ProophPayloadTrait;

abstract class BaseCommand extends ProophCommand implements ProophPayloadConstructable
{
    use ProophPayloadTrait;

    protected function setPayload(array $payload): void
    {
        $this->validate($payload);
        $this->payload = $payload;
    }

    abstract public function validate(array $payload): void;
}
