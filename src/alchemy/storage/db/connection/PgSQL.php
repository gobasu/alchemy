<?php
namespace alchemy\storage\db\connection;
use alchemy\storage\db\Model;

class PgSQLException extends SQLException {}

/**
 * PgSQL Connection class
 */

class PgSQL extends SQL
{
    /**
     * @param $host
     * @param $user
     * @param $password
     * @param $db
     */
    public function __construct($host, $user, $password, $db)
    {
        $dsn = 'pgsql:dbname=' . $db . ';host=' . $host;
        parent::__construct($dsn, $user, $password, array(
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_PERSISTENT => true // use persistent on

        ));
    }
}