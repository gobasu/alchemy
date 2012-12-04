<?php
namespace alchemy\storage\db;
/**
 * IDriver
 *
 * @author: lunereaper
 */

interface IConnection
{
    public function save(Entity $entity);
    public function delete(Entity $entity);
    public function get(Entity $entity);
}
