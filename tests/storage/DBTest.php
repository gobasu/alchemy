<?php
use alchemy\storage\DB;

class DBTest extends PHPUnit_Framework_TestCase
{
    public static function setUpBeforeClass()
    {
        self::$connection = new DummyConnection();
    }

    public function testAdd()
    {
        DB::add(self::$connection);
    }

    /**
     * @depends testAdd
     */
    public function testGet()
    {
        $connection = DB::get();
        $this->assertSame(self::$connection, $connection);
    }

    /**
     * @var \alchemy\storage\db\IConnection
     */
    protected static $connection;
}