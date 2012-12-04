<?php
namespace alchemy\storage\db;
use alchemy\storage\DB;
/**
 * Collection
 *
 * @author: lunereaper
 */

abstract class Collection implements \alchemy\object\ILoadable
{
    /**
     * Constructor
     *
     * @param IConnection $connection
     */
    protected function __construct(IConnection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * Returns connection name override this one
     * to make a possiblity to use multiple different
     * DB connections in one app
     * @example
     * DB::add($conn, 'someName');
     *
     * class SomeCollection extends Collection
     * {
     *  public static function getConnectionName()
     *  {
     *      return 'someName';
     *  }
     * }
     *
     *
     * @return string
     */
    protected static function getDBName()
    {
        return DB::DEFAULT_NAME;
    }

    /**
     * Creates and returns an instance of collection if none
     * exists in memory or returns previously created one
     *
     * @return Collection
     */
    public static function load()
    {
        $class = get_called_class();
        if (isset(self::$loaded[$class])) {
            return self::$loaded[$class];
        }
        return self::$loaded[$class] = new $class(DB::get($class::getDBName()));
    }

    /**
     * @return IConnection
     */
    public function getConnection()
    {
        return $this->connection;
    }

    /**
     * Array containing loaded collections
     *
     * @var array
     */

    protected static $loaded;

    /**
     * DB Driver used by collection
     *
     * @var IConnection
     */
    protected $connection;

}
