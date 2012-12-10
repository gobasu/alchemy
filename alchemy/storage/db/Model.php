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
     * Construct new Model with given PK
     *
     * @param mixed $pkValue id or pk value
     */
    public function __construct($pkValue = null)
    {
        if ($pkValue === null) {
            return;
        }

        $this->{self::getSchema()->getPKProperty()->getName()} = $pkValue;
    }

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
        return $this->{self::getSchema()->getPKProperty()->getName()};
    }

    private function setPK($value)
    {
        $this->{self::getSchema()->getPKProperty()->getName()} = $value;
    }

    public static function findOne($query, $sort = null)
    {
        $schema = self::getSchema();
        $connection = DB::get($schema->getConnectionName());
        return $connection->findOne($schema, $query, $sort);

    }

    public static function findAll(array $query, array $sort = null)
    {
        $schema = self::getSchema();
        $connection = DB::get($schema->getConnectionName());
        return $connection->findAll($schema, $query, $sort);
    }

    /**
     * Calculates the changes and writes them to $this->changes array
     *
     * @param string $name
     * @param string|int\float $value
     * @throws ModelException
     */
    public function __set($name, $value)
    {
        if (!self::getSchema()->propertyExists($name)) {
            throw new ModelException('Non existing property `' . $name . '` in model ' . get_called_class());
        }
        if (!isset($this->changes[$name])) {
            if ($this->{$name} != $value) {
                $this->changes[$name] = $value;
                $this->isChanged = true;
            }
            return;
        }

        if ($this->changes[$name] != $value) {

            //value was changed to existing in storage
            if ($value == $this->{$name}) {
                unset ($this->changes[$name]);
                $this->isChanged = false;
                return;
            }

            $this->changes[$name] = $value;
            $this->isChanged = true;
        }

    }

    public function __get($name)
    {
        if (isset($this->changes[$name])) {
            return $this->changes[$name];
        }

        return $this->{$name};
    }

    public function save()
    {
        $this->onSave();
        $schema = self::getSchema();
        $connection = DB::get($schema->getConnectionName());

        $connection->save($this);
        $this->onPersist();
        $this->applyChanges();
        return true;

    }

    public function delete()
    {
        $this->onDelete();
        $schema = self::getSchema();
        $connection = DB::get($schema->getConnectionName());

        $connection->delete($this);
    }

    public function forceSave()
    {
        $this->forceSave = true;
    }

    public function serialize()
    {
        $schema = self::getSchema();
        $serialized = array();
        foreach ($schema as $field)
        {

        }
        return $serialized;
    }

    public function getChanges()
    {
        return $this->changes;
    }

    public function validate()
    {

    }

    private function applyChanges()
    {
        foreach ($this->changes as $key => $value) {
            $this->{$key} = $value;
        }
        $this->changes = array();
        $this->isChanged = false;
    }

    public function isChanged()
    {
        return $this->isChanged;
    }

    protected $forceSave = false;

    protected $changes = array();

    protected $isChanged = false;

    /**
     * Model's schema
     *
     * @var array of \alchemy\db\ISchema
     */
    protected static $schemaList = array();

}
