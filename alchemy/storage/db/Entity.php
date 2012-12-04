<?php
namespace alchemy\storage\db;

use alchemy\app\Loader;

class EntityException extends \Exception {}
/**
 * Entity
 *
 * Is standalone representation of record in database
 * The framework handles 6 datatypes:
 * -bool
 * -number
 * -string
 * -blob
 * -date
 * -enum
 *
 * When defining a property in entity you should use 'Param' annotation
 * to tell what behavior should be applied to given parameter, for example
 * \@Param(type=bool)
 * Additionaly you can dynamically map class property name into your db's
 * property name by using attribute name, example
 * \@Param(type=number,name=my_db_column)
 *
 *
 * @author: lunereaper
 */
abstract class Entity
{
    public function __construct(array $data = null)
    {
        if ($data !== null) {
            $this->fetch($data);
        }
    }
    /**
     * @return ISchema
     */
    public static function getSchema()
    {
        $class = get_called_class();
        if (isset(self::$schemaList[$class])) {
            return self::$schemaList[$class];
        }
        return self::$schemaList[$class] = SchemaBuilder::getSchema($class);
    }

    public function onSave()  {}
    public function onPersist() {}
    public function onDelete() {}
    public function onGet() {}

    /**
     * @param $pk
     * @return Entity
     */
    public static function get($pk)
    {
        $class = get_called_class();
        $entity = new $class();
        $schema = $entity->getSchema();
        $collection = $schema->getCollection();
        $db = $collection::load()->getConnection();
        $db->get($entity, $pk);
        return $entity;
    }

    public static function findOne()
    {

    }

    public static function findAll()
    {

    }

    public function __set($name, $value)
    {
        $this->getSchema();
    }

    public function __get($name)
    {

    }

    public function save()
    {
        $this->onSave();

        $this->onPersist();
    }

    public function delete()
    {
        $this->onDelete();
    }

    public function forceSave()
    {
        $this->forceSave = true;
    }

    public function serialize()
    {

    }

    public function validate()
    {

    }

    protected function fetch(array $data)
    {
        $this->onGet();

    }


    private $forceSave = false;

    /**
     * Entity's schema
     * array(
     *  '{fieldName}' => Entity::TYPE_*
     * )
     * @var array of \alchemy\db\ISchema
     */
    protected static $schemaList = array();
    /**
     * @var ISchema
     */
    protected $schema;
}
