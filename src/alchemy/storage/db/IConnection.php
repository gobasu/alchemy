<?php
namespace alchemy\storage\db;
/**
 * IDriver
 *
 * @author: lunereaper
 */

interface IConnection
{
    public function save(Model $model);
    public function delete(Model $model);
    public function get($modelName, $parameters);
}
