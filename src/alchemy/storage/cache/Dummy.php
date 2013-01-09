<?php
namespace alchemy\storage\cache;

class Dummy implements IDriver
{
    /**
     * Constructor
     *
     * @param array $cacheArray sets dummy's cache to this array
     */
    public function __construct (&$cacheArray = null)
    {
        if ($cacheArray !== null) {
            $this->data = &$cacheArray;
        } else {
            $this->data = array();
        }
    }

    public static function isAvailable()
    {
        return true;
    }

    public function get($key)
    {
        return isset($this->data[$key]) ? $this->data[$key] : null;
    }

    public function set($key, $value, $ttl = null)
    {
        $this->data[$key] = $value;
    }

    public function delete($key)
    {
        unset($this->data[$key]);
    }

    public function exists($key)
    {
        return isset($this->data[$key]);
    }

    public function flush()
    {
        $this->data = array();
    }
    
    private $data;
}