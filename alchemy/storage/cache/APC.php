<?php
namespace alchemy\storage\cache;

class APC implements IDriver
{
    public static function isAvailable()
    {
        return extension_loaded('apc');
    }

    public function get($key)
    {
        if (isset(self::$cache[$key])) return self::$cache[$key];
        return self::$cache[$key] = apc_fetch($key);
    }

    public function set($key, $value, $ttl = null)
    {
        self::$cache[$key] = $value;
        return apc_store($key, $value, $ttl);
    }

    public function delete($key)
    {
        unset(self::$cache[$key]);
        return apc_delete($key);
    }

    public function exists($key)
    {
        return apc_exists($key);
    }

    public function flush()
    {
        return apc_clear_cache(self::APC_USER_CACHE_NAME);
    }
    
    private static $cache = array();

    const APC_USER_CACHE_NAME = 'user';
}