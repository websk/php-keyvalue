<?php

namespace WebSK\KeyValue;

use WebSK\Utils\Sanitize;
use WebSK\Entity\EntityRepository;

/**
 * Class KeyValueRepository
 * @package WebSK\KeyValue
 */
class KeyValueRepository extends EntityRepository
{
    /**
     * @param string $key
     * @return int|null
     */
    public function findIdByKey(string $key): ?int
    {
        $db_table_name = $this->getTableName();
        $db_id_field_name = $this->getIdFieldName();

        return $this->db_service->readField(
            'SELECT ' . Sanitize::sanitizeSqlColumnName($db_id_field_name)
            . ' FROM ' . Sanitize::sanitizeSqlColumnName($db_table_name)
            . ' WHERE ' . Sanitize::sanitizeSqlColumnName(KeyValue::_NAME) . '=?',
            [$key]
        );
    }
}
