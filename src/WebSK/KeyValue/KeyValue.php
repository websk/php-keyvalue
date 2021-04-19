<?php

namespace WebSK\KeyValue;

use WebSK\Entity\Entity;

/**
 * Class KeyValue
 * @package WebSK\KeyValue
 */
class KeyValue extends Entity
{
    const DB_TABLE_NAME = 'key_value';

    const _NAME = 'name';
    protected string $name;

    const _DESCRIPTION = 'description';
    protected string $description = '';

    const _VALUE = 'value';
    protected string $value = '';

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
