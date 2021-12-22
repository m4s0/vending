<?php

declare(strict_types=1);

namespace Common\Infrastructure\Projection;

use Doctrine\DBAL\Connection;

abstract class BaseFinder
{
    /**
     * @var Connection
     */
    protected $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
        $this->connection->setFetchMode(\PDO::FETCH_ASSOC);
    }
}
