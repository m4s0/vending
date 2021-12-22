<?php

declare(strict_types=1);

namespace Core\Domain\Model\Coin;

use Assert\Assertion;
use Common\Domain\ValueObject\Identity\BaseUuid;

final class CoinId extends BaseUuid
{
    public function validate(string $uuid): void
    {
        try {
            Assertion::uuid($uuid);
        } catch (\Exception $e) {
            throw new \InvalidArgumentException('Invalid CoinId because: '.$e->getMessage());
        }
    }
}
