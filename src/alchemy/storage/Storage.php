<?php
/**
 * Alchemy Framework (http://alchemyframework.org/)
 *
 * @link      http://github.com/dkraczkowski/alchemy for the canonical source repository
 * @copyright Copyright (c) 2012-2013 Dawid Kraczkowski
 * @license   https://raw.github.com/dkraczkowski/alchemy/master/LICENSE New BSD License
 */
namespace alchemy\storage;
use alchemy\storage\IStore;

class StorageException extends \Exception {}
/**
 * Storage Class
 *
 * Storage registry class
 */
class Storage
{
    /**
     * Adds new connection to driver class
     *
     * @param IStorage $storage
     */
    public static function add(IStorage $storage)
    {
        $className = get_class($storage);
        self::$storages[$className] = $storage;
        self::$defaultStorage = $className;
    }

    /**
     * @param string $name connection name
     * @return IStorage|\PDO
     * @throws StorageException
     */
    public static function get($id = null)
    {
        if (!$id) {
            $id = self::$defaultStorage;
        }

        if (!isset(self::$storages[$id])) {
            if (class_exists($id) && is_subclass_of($id, 'alchemy\storage\IStorage')) {
                self::$storages[$id] = new $id;
            } else {
                throw new StorageException('Storage `' . $id . '` is not defined or does not implement alchemy\storage\store\IStorage');
            }
        }

        return self::$storages[$id];
    }

    public static function setDefaultStorage($className)
    {
        if (class_exists($className) && is_subclass_of($className, 'alchemy\storage\IStorage')) {
            self::$defaultStorage = $className;
        }
    }

    public static function getDefaultStorage()
    {
        return self::$defaultStorage;
    }

    protected static $defaultStorage = 'alchemy\storage\sql\SQLite';
    protected static $storages = array();
}
