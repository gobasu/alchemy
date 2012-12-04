<?php
namespace alchemy\storage\db\connection;
use alchemy\storage\db\Entity;
/**
 * MySQL
 *
 * @author: lunereaper
 */

class MySQL extends \PDO implements \alchemy\storage\db\IConnection
{
    public function __construct($host, $user, $password, $db)
    {
        $dsn = 'mysql:dbname=' . $db . ';host=' . $host;
        parent::__construct($dsn, $user, $password, array(
            \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\'',
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION
        ));
    }

    public function save(Entity $entity)
    {

    }

    public function delete(Entity $entity)
    {

    }

    public function get(Entity $entity)
    {

    }

    const INSERT_SQL    = 'INSERT INTO `%s`(%s) VALUES(%s)';
    const UPDATE_SQL    = 'UPDATE `%s` SET %s WHERE %s';
    const DELETE_SQL    = 'DELETE FROM `%s` WHERE %s';
    const GET_SQL       = 'SELECT %s FROM `%s` WHERE %s';
}
