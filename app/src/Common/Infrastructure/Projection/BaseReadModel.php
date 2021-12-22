<?php

declare(strict_types=1);

namespace Common\Infrastructure\Projection;

use Doctrine\DBAL\Connection;
use Prooph\EventStore\Projection\AbstractReadModel;

abstract class BaseReadModel extends AbstractReadModel
{
    /**
     * @var Connection
     */
    protected $connection;

    protected $tableName;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
        $this->tableName  = $this->getTableName();
    }

    abstract public function getTableName(): string;

    abstract public function init(): void;

    public function isInitialized(): bool
    {
        $sql = "SHOW TABLES LIKE '$this->tableName';";

        $statement = $this->connection->prepare($sql);
        $statement->execute();

        $result = $statement->fetch();

        if (false === $result) {
            return false;
        }

        return true;
    }

    public function reset(): void
    {
        $sql = "TRUNCATE TABLE $this->tableName;";

        $statement = $this->connection->prepare($sql);
        $statement->execute();
    }

    public function delete(): void
    {
        $sql = "DROP TABLE $this->tableName;";

        $statement = $this->connection->prepare($sql);
        $statement->execute();
    }

    protected function insert(array $data): void
    {
        $this->connection->insert($this->tableName, $data);
    }

    protected function update(array $data, array $identifier): void
    {
        $this->connection->update(
            $this->tableName,
            $data,
            $identifier
        );
    }
}
