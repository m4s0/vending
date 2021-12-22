<?php

declare(strict_types=1);

namespace Common\Application\Service;

use Prooph\Common\Messaging\MessageFactory;
use Prooph\ServiceBus\CommandBus;
use Prooph\ServiceBus\QueryBus;

abstract class BaseService
{
    protected $queryBus;
    protected $commandBus;
    protected $messageFactory;

    public function __construct(
        CommandBus $commandBus,
        QueryBus $queryBus,
        MessageFactory $messageFactory
    ) {
        $this->commandBus = $commandBus;
        $this->queryBus = $queryBus;
        $this->messageFactory = $messageFactory;
    }
}
