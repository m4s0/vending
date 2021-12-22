<?php

declare(strict_types=1);

namespace Core\Infrastructure\Projection\Item;

use Common\Infrastructure\Projection\BaseReadModel;
use Core\Infrastructure\Projection\Table;

final class ItemReadModel extends BaseReadModel
{
    public function getTableName(): string
    {
        return Table::ITEM;
    }

    public function init(): void
    {
        $sql = <<<EOT
CREATE TABLE `$this->tableName` (
  `id` varchar(36) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `price` decimal(3,2) NOT NULL,
  `amount` smallint NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY unique_name (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
EOT;

        $statement = $this->connection->prepare($sql);
        $statement->execute();
    }
}
