<?php
/**
 * Copyright (C) 2012 Dawid Kraczkowski
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to
 * deal in the Software without restriction, including without limitation the
 * rights to use, copy, modify, merge, publish, distribute, sublicense, and/or
 * sell copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED,
 * INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR
 * A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT
 * HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF
 * CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE
 * OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */
namespace alchemy\storage\db;
use alchemy\storage\DB;
use alchemy\app\Loader;
use alchemy\event\EventDispatcher;

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
abstract class Model extends EventDispatcher
{
    /**
     * Construct new Model
     *
     * @param string|int|array $data pk value or data
     */
    public function __construct($data = null)
    {
        if ($data === null) {
            return;
        } elseif (is_string($data) || is_numeric($data)) {
            $this->{self::getSchema()->getPKProperty()->getName()} = $data;
        } elseif (is_array($data)) {
            $this->change($data);
        } else {
            throw new ModelException('Model::__construct() accepts string|int|array, ' . gettype($data) . ' passed');
        }
    }

    /**
     * Sets multiple parameters in model
     *
     * @param array $data
     */
    public function change(array $data)
    {
        foreach ($data as $property => $value) {
            $this->__set($property, $value);
        }
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

    /**
     * Gets data corresponding to given PK from current connection
     *
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

    /**
     * Finds first object which matches given query
     *
     * @param $query
     * @param array $sort
     * @return Model
     */
    public static function findOne(array $query = array(), array $sort = null)
    {
        $schema = self::getSchema();
        $connection = DB::get($schema->getConnectionName());
        return $connection->findOne($schema, $query, $sort);

    }

    /**
     * Finds all objects in DB which match given query
     *
     * @param array $query
     * @param array $sort sorts objects by given field
     * @see More in coresponding to model IConnection handler
     * @return array
     */
    public static function find(array $query = array(), array $sort = null)
    {
        $schema = self::getSchema();
        $connection = DB::get($schema->getConnectionName());
        return $connection->find($schema, $query, $sort);
    }

    public static function findAndRemove($query, $returnData = false)
    {
        $schema = self::getSchema();
        $connection = DB::get($schema->getConnectionName());
        return $connection->findAndRemove($schema, $query, $returnData);
    }

    public static function findAndModify(array $query = null, array $update, $returnData = false)
    {
        $schema = self::getSchema();
        $connection = DB::get($schema->getConnectionName());
        return $connection->findAndModify($schema, $query, $update, $returnData);
    }

    /**
     * Provides interface for custom queries for details
     * look into used connection class
     */
    public static function query(/** mutable **/)
    {
        $schema = self::getSchema();
        $connection = DB::get($schema->getConnectionName());
        if (!method_exists($connection, 'query')) {
            throw new ModelException(get_class($connection) . ' does not support custom queries');
        }
        call_user_func_array(array($connection, 'query'), func_get_args());
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

    /**
     * Gets model's property
     *
     * @param $name
     * @return mixed
     */
    public function __get($name)
    {
        if (isset($this->changes[$name])) {
            return $this->changes[$name];
        }

        return $this->{$name};
    }

    /**
     * Override this method if you need
     * Called everytime when framework is trying to put model
     * to the database
     */
    public function onSave()
    {}

    /**
     * Override this method if you need
     * Called everytime when model have been saved to database
     */
    public function onPersists()
    {}

    /**
     * Override this method if you need
     * Called everytime when model was purged from db
     */
    public function onDelete()
    {}

    /**
     * Override this method if you need
     * Called when record was fetched from DB
     */
    public function onGet()
    {}

    public function isNew()
    {
        return $this->getPK() == null ? true : false;
    }

    public function isChanged()
    {
        return $this->isChanged;
    }

    /**
     * Gets model's PK value
     *
     * @return mixed
     */
    public function getPK()
    {
        return $this->{self::getSchema()->getPKProperty()->getName()};
    }

    /**
     * Set model's PK value
     *
     * @param $value
     */
    private function setPK($value)
    {
        $this->{self::getSchema()->getPKProperty()->getName()} = $value;
    }

    /**
     * Persists model to database
     *
     * @return bool
     */
    public function save()
    {
        $schema = self::getSchema();
        $connection = DB::get($schema->getConnectionName());
        $this->onSave();
        $connection->save($this);
        $this->applyChanges();
        $this->onPersists();
        return true;

    }

    /**
     * Purges model from database
     */
    public function delete()
    {
        $schema = self::getSchema();
        $connection = DB::get($schema->getConnectionName());
        $this->onDelete();
        $connection->delete($this);

        //set model as fresh one after deletion
        $this->isChanged = true;
        $this->changes = $this->serialize();
        $this->setPK(null);
    }

    /**
     * Forces the model persisting even if no changes were applied to model
     */
    public function forceSave()
    {
        $this->forceSave = true;
        $this->isChanged = true;
    }

    /**
     * Returns serialized model to an assoc. array (key -> value)
     * @return array
     */
    public function serialize()
    {
        $schema = self::getSchema();
        $serialized = array();
        foreach ($schema as $name => $property) {
            if (isset($this->changes[$name])) {
                $serialized[$name] = $this->changes[$name];
            } else {
                $serialized[$name] = $this->{$name};
            }
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

    /**
     * Dispatches an event to EventHub
     *
     * @param \alchemy\event\Event $e
     */
    public function dispatch(\alchemy\event\Event $e)
    {
        \alchemy\event\EventHub::dispatch($e);
        parent::dispatch($e);
    }

    /**
     * Gets model's connection
     *
     * @return IConnection|\PDO
     */
    protected static function getConnection()
    {
        $schema = self::getSchema();
        return DB::get($schema->getConnectionName());
    }

    private function applyChanges()
    {
        foreach ($this->changes as $key => $value) {
            $this->{$key} = $value;
        }
        $this->changes = array();
        $this->isChanged = false;
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
