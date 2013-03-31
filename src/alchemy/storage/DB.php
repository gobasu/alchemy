<?php
/**
 * Alchemy Framework (http://alchemyframework.org/)
 *
 * @link      http://github.com/dkraczkowski/alchemy for the canonical source repository
 * @copyright Copyright (c) 2012-2013 Dawid Kraczkowski
 * @license   https://raw.github.com/dkraczkowski/alchemy/master/LICENSE New BSD License
 */
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
        self::$connections[$name] = $driver;
    }

    /**
     * @param string $name connection name
     * @return IConnection|\PDO
     * @throws DBException
     */
    public static function get($name = self::DEFAULT_NAME)
    {
        if (!isset(self::$connections[$name])) {
            throw new DBException('Connection `' . $name . '` is not defined');
        }

        return self::$connections[$name];
    }

    const DEFAULT_NAME = 'default';
    protected static $connections = array();
}
