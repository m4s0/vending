<?php

declare(strict_types=1);

namespace Common\Domain\Entity;

interface EntityInterface
{
    public function equals(EntityInterface $other): bool;
}
