<?php
namespace alchemy\storage;
use alchemy\storage\db\IConnection;
class DBException extends \Exception {}
/**
 * DB
 *
 * Connection registry class
 */
class DB
{
    /**
     * Adds new connection to driver class
     *
     * @param db\IConnection $driver
     * @param string $name
     */
    public static function add(IConnection $driver, $name = self::DEFAULT_NAME)
    {
        self::$connection[$name] = $driver;
    }

    /**
     * @param string $name connection name
     * @return IDriver
     * @throws DBException
     */
    public static function get($name = self::DEFAULT_NAME)
    {
        if (!isset(self::$connection[$name])) {
            throw new DBException('Connection `' . $name . '` is not defined');
        }

        return self::$connection[$name];
    }

    const DEFAULT_NAME = 'default';
    protected static $connection = array();
}
