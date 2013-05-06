<?php
use alchemy\storage\Model;
use alchemy\storage\ISchema;
use alchemy\storage\IStorage;
/**
 * Dummy Connection class
 */

class DummyConnection implements IStorage
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