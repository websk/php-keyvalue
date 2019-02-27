<?php

namespace WebSK\KeyValue;

use WebSK\Entity\Entity;

/**
 * Class KeyValue
 * @package WebSK\KeyValue
 */
class KeyValue extends Entity
{
    const ENTITY_SERVICE_CONTAINER_ID = 'keyvalue.keyvalue_service';
    const ENTITY_REPOSITORY_CONTAINER_ID = 'keyvalue.keyvalue_repository';
    const DB_TABLE_NAME = 'key_value';

    const _NAME = 'name';
    /** @var string */
    protected $name;

    const _DESCRIPTION = 'description';
    /** @var string */
    protected $description;

    const _VALUE = 'value';
    /** @var string */
    protected $value = "";

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @param string $value
     */
    public function setValue(string $value): void
    {
        $this->value = $value;
    }
}
