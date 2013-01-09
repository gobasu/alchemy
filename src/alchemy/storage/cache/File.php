<?php
namespace alchemy\storage\cache;
class FileException extends \alchemy\storage\CacheException {}
class File implements IDriver
{

    /**
     * File oriented cache
     *
     * @param $filename
     */
    public function __construct ($filename)
    {
        if (!is_writable($filename)) {
            throw new FileException('Cache file `' . $filename . '` is not writeable!');
        }
        $this->filename = $filename;
        $this->data = json_decode(file_get_contents($filename), true);
    }

    public static function isAvailable()
    {
        return true;
    }

    public function get($key)
    {
        return isset($this->data[$key]) ?
            ($this->data[$key]['ttl'] < time() ? $this->data[$key]['value'] : $this->delete($key)) :
            null;
    }

    public function set($key, $value, $ttl = null)
    {
        $this->data[$key] = array('ttl' => time() + $ttl, 'value' => $value);
        $this->write();
    }

    public function delete($key)
    {
        unset($this->data[$key]);
        $this->write();
    }

    public function exists($key)
    {
        return isset($this->data[$key]) ? $this->data[$key]['ttl'] > time() : false;
    }

    public function flush()
    {
        $this->data = array();
        file_put_contents($this->filename, '{}');
    }

    protected function write()
    {
        file_put_contents($this->filename, json_encode($this->data));
    }

    private $filename;
    private $data = array();
}