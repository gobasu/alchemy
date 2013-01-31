<?php
namespace alchemy\storage\db\connection;
use alchemy\storage\db\Model;
use alchemy\storage\db\ISchema;

class MySQLException extends SQLException {}

/**
 * MySQL Connection class
 */

class MySQL extends SQL
{
    /**
     * @param $host
     * @param $user
     * @param $password
     * @param $db
     */
    public function __construct($host, $user, $password, $db)
    {
        $dsn = 'mysql:dbname=' . $db . ';host=' . $host;
        parent::__construct($dsn, $user, $password, array(
            \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\'',
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_PERSISTENT => true // use persistent on

        ));
    }
}