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
    public function findIdByKey(string $key): ?int
    {
        $db_table_name = $this->getTableName();
        $db_id_field_name = $this->getIdFieldName();

        $id = $this->db_service->readField(
            'SELECT ' . Sanitize::sanitizeSqlColumnName($db_id_field_name)
            . ' FROM ' . Sanitize::sanitizeSqlColumnName($db_table_name)
            . ' WHERE ' . Sanitize::sanitizeSqlColumnName(KeyValue::_NAME) . '=?',
            [$key]
        );

        if ($id === false) {
            return null;
        }
        if (is_int($id)) {
            return $id;
        }
        if (is_string($id) && preg_match('/^-?\\d+$/D', $id) === 1) {
            return (int)$id;
        }

        throw new \UnexpectedValueException('KeyValue ID must be an integer or an integer string.');
    }
}
