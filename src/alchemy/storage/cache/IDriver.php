<?php
namespace alchemy\storage\cache;
/**
 * IDriver
 *
 * @author: lunereaper
 */

interface IDriver
{
    /**
     * Gets item from cache
     */
    public function get($key);

    /**
     * Sets item to cache
     */
    public function set($key, $value, $ttl = null);

    /**
     * Deletes cache's item
     */
    public function delete($key);

    /**
     * Checks whatever item exists in cache
     */
    public function exists($key);

    /**
     * Clears whole cache
     */
    public function flush();

    /**
     * Checks if cache driver is avaible
     * @return boolean
     */
    public static function isAvailable();
}
