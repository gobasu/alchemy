<?php
use alchemy\storage\Storage;

class StorageTest extends PHPUnit_Framework_TestCase
{
    public static function setUpBeforeClass()
    {
        self::$connection = new DummyConnection();
    }

    public function testAdd()
    {
        Storage::add(self::$connection);
    }

    /**
     * @depends testAdd
     */
    public function testGet()
    {
        $connection = Storage::get();
        $this->assertSame(self::$connection, $connection);
    }

    /**
     * @var \alchemy\storage\IStorage
     */
    protected static $connection;
}