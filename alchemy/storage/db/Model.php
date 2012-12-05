<?php
namespace alchemy\storage\db;
use alchemy\storage\DB;
use alchemy\app\Loader;

class ModelException extends \Exception {}
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
abstract class Model
{
    /**
     * @return IConnection|\PDO
     */
    protected static function getConnection()
    {
        $schema = self::getSchema();
        return DB::get($schema->getConnection());
    }

    /**
     * Gets schema object corresponding to given model class
     * If schema was not loaded than generates a new one
     *
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

    /**
     * @param $pk
     * @return Model
     */
    public static function get($pk)
    {
        $modelName = get_called_class();

        $schema = self::getSchema();

        $connection = DB::get($schema->getConnectionName());
        return $connection->get($modelName, $pk);
    }

    public function getPK()
    {
        return $this->{self::getSchema()->getPKProperty()->getLocalName()};
    }

    private function setPK($value)
    {
        $this->{self::getSchema()->getPKProperty()->getLocalName()} = $value;
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


    private $forceSave = false;

    /**
     * Model's schema
     *
     * @var array of \alchemy\db\ISchema
     */
    protected static $schemaList = array();

}
