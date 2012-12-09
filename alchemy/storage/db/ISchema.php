<?php
namespace alchemy\storage\db;

interface ISchema
{
    /**
     * @return Property
     */
    public function getPKProperty();

    /**
     * @return array
     */
    public function getPropertyList();

    /**
     * @return string
     */
    public function getConnectionName();

    /**
     * @param string $name
     * @return Property
     */
    public function getProperty($name);

    /**
     * @return string
     */
    public function getCollectionName();

    /**
     * @param string $name
     * @return bool
     */
    public function propertyExists($name);
}
