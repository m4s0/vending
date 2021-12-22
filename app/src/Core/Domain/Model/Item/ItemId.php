<?php

declare(strict_types=1);

namespace Core\Domain\Model\Item;

use Assert\Assertion;
use Common\Domain\ValueObject\Identity\BaseUuid;

final class ItemId extends BaseUuid
{
    public function validate(string $uuid): void
    {
        try {
            Assertion::uuid($uuid);
        } catch (\Exception $e) {
            throw new \InvalidArgumentException('Invalid ItemId because: '.$e->getMessage());
        }
    }
}
