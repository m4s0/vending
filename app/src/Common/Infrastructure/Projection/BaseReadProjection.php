<?php

declare(strict_types=1);

namespace Common\Infrastructure\Projection;

use Prooph\Bundle\EventStore\Projection\ReadModelProjection;
use Prooph\EventStore\Projection\ReadModelProjector;

abstract class BaseReadProjection implements ReadModelProjection
{
    public function project(ReadModelProjector $projector): ReadModelProjector
    {
        $events = $this->listenerList();
        $projector->fromStream('event_stream')
            ->when($events);

        return $projector;
    }

    abstract public function listenerList(): array;
}
