<?php

declare(strict_types=1);

namespace Core\Infrastructure\Projection\Coin;

use Common\Infrastructure\Projection\BaseReadModel;
use Core\Infrastructure\Projection\Table;

final class CoinReadModel extends BaseReadModel
{
    public function getTableName(): string
    {
        return Table::COIN;
    }

    public function init(): void
    {
        $sql = <<<EOT
CREATE TABLE `$this->tableName` (
  `id` varchar(36) COLLATE utf8_unicode_ci NOT NULL,
  `value` decimal(3,2) NOT NULL,
  `amount` smallint NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY unique_value (`value`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
EOT;

        $statement = $this->connection->prepare($sql);
        $statement->execute();
    }
}
