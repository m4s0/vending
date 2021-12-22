<?php

declare(strict_types=1);

namespace Common\Domain\Event;

use Prooph\EventSourcing\AggregateChanged;

abstract class BaseAggregateChanged extends AggregateChanged
{
}
