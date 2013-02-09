<?php
use alchemy\storage\db\IConnection;
use alchemy\storage\db\Model;
use alchemy\storage\db\ISchema;
/**
 * Dummy Connection class
 */

class DummyConnection implements IConnection
{
    public function save(Model $model)
    {

    }

    public function delete(Model $model)
    {

    }

    public function get($modelName, $parameters)
    {

    }

    public function find(ISchema $schema, array $query = null, array $sort = null)
    {

    }

    public function findOne(ISchema $schema, array $query = null, array $sort = null)
    {

    }

    public function findAndModify(ISchema $schema, array $query = null, array $update, $returnData = false)
    {

    }

    public function findAndRemove(ISchema $schema, array $query = null, $returnData = false)
    {

    }

}