<?php
/**
 *
 */
set_include_path(get_include_path() . PATH_SEPARATOR . dirname(realpath("../src/")));
require_once 'src/alchemy/app/Application.php';
use alchemy\storage\db\IConnection;
use alchemy\storage\db\Model;
use alchemy\storage\db\ISchema;
use alchemy\storage\session\IHander;

/**
 * Test resources and callbacks
 */

function a()
{
    return 1;
}
function b()
{
    return 2;
}
class TestResource
{
    public function a()
    {
        return a();
    }

    public static function b()
    {
        return b();
    }
    
    public function throwError($msg)
    {
        throw new Exception($msg);
    }
}

/**
 * Dump Events
 */

class OnEvent extends \alchemy\event\Event {}
class OnParentEvent extends OnEvent {}

/**
 * Dummy Connection class
 */

class DummyConnection implements IConnection
{
    public function save(Model $model)
    {
        // TODO: Implement save() method.
    }

    public function delete(Model $model)
    {
        // TODO: Implement delete() method.
    }

    public function get($modelName, $parameters)
    {
        // TODO: Implement get() method.
    }

    public function find(ISchema $schema, array $query = null, array $sort = null)
    {
        // TODO: Implement find() method.
    }

    public function findOne(ISchema $schema, array $query = null, array $sort = null)
    {
        // TODO: Implement findOne() method.
    }

    public function findAndModify(ISchema $schema, array $query = null, array $update, $returnData = false)
    {
        // TODO: Implement findAndModify() method.
    }

    public function findAndRemove(ISchema $schema, array $query = null, $returnData = false)
    {
        // TODO: Implement findAndRemove() method.
    }

}