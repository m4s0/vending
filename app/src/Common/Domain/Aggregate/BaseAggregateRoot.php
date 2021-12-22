<?php

declare(strict_types=1);

namespace Common\Domain\Aggregate;

use Common\Domain\Entity\EntityInterface;
use Prooph\EventSourcing\AggregateChanged as ProophAggregateChanged;
use Prooph\EventSourcing\AggregateRoot as ProophAggregateRoot;

abstract class BaseAggregateRoot extends ProophAggregateRoot implements EntityInterface
{
    abstract public function equals(EntityInterface $other): bool;

    /**
     * Apply given event.
     */
    protected function apply(ProophAggregateChanged $e): void
    {
        $handler = $this->determineEventHandlerMethodFor($e);

        if (!\method_exists($this, $handler)) {
            throw new \RuntimeException(\sprintf('Missing event handler method %s for aggregate root %s', $handler, \get_class($this)));
        }

        $this->{$handler}($e);
    }

    protected function determineEventHandlerMethodFor(ProophAggregateChanged $e): string
    {
        return 'when'.\implode(\array_slice(\explode('\\', \get_class($e)), -1));
    }
}
