<?php
namespace alchemy\storage\db\connection;
use alchemy\storage\db\Model;
/**
 * MySQL
 *
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

    public function save(Model $model)
    {

    }

    public function delete(Model $model)
    {

    }

    public function get($model, $pkValue)
    {
        $schema = $model::getSchema();
        $fieldList = '`' . implode('`,`', $schema->getPropertyList()) . '`';
        $pkField = $schema->getPKProperty();
        $where = '`' . $pkField->getExternalName() . '` = :pk';
        $sql = sprintf(self::GET_SQL, $fieldList, $schema->getCollectionName(), $where);
        $query = $this->prepare($sql);
        $query->bindValue(':pk', $pkValue);
        $query->execute();
        return $query->fetchObject($model);
    }

    const INSERT_SQL    = 'INSERT INTO `%s`(%s) VALUES(%s)';
    const UPDATE_SQL    = 'UPDATE `%s` SET %s WHERE %s';
    const DELETE_SQL    = 'DELETE FROM `%s` WHERE %s';
    const GET_SQL       = 'SELECT %s FROM `%s` WHERE %s';
}
