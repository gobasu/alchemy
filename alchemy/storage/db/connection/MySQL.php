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
        if (!$model->isChanged()) {
            return;
        }
        $pk = $model->getPK();
        if ($pk) {
            $this->update($model);
        } else {
            $this->insert($model);
        }
    }

    private function update(Model $model)
    {
        $schema = $model::getSchema();
        $pkField = $schema->getPKProperty();
        $where = '`' . $pkField->getName() . '` = :pk';

        $changes = $model->getChanges();
        $fields = array();
        foreach ($changes as $key => $value) {
            $fields[] = '`' . $key . '` = :' . $key;
        }
        $sql = sprintf(self::UPDATE_SQL, $schema->getCollectionName(), implode(',', $fields), $where);
        $query = $this->prepare($sql);
        foreach ($changes as $key => $value) {
            $query->bindValue($key, $value);
        }
        $query->bindValue('pk', $model->getPK());
        $query->execute();

    }

    private function insert(Model $model)
    {
        $schema = $model::getSchema();
        $pkField = $schema->getPKProperty();
        $fields = array();
        $binds = array();
        foreach ($schema as $field) {
            $fields[] = '`' . $field->getName() . '`';
            $binds[] = ':' . $field->getName();
        }
        $sql = sprintf(self::INSERT_SQL, $schema->getCollectionName(), implode(',', $fields), implode(',', $binds));
        $query = $this->prepare($sql);
        foreach ($schema as $field) {
            $name = $field->getName();
            $query->bindValue($name, $model->{$name});
        }
        $query->execute();

        $model->$this->lastInsertId();

    }

    public function delete(Model $model)
    {

    }

    public function get($model, $pkValue)
    {
        $schema = $model::getSchema();
        $fieldList = '`' . implode('`,`', $schema->getPropertyList()) . '`';
        $pkField = $schema->getPKProperty();
        $where = '`' . $pkField->getName() . '` = :pk';
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
