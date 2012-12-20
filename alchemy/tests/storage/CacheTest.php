<?php
use alchemy\storage\Cache;
use alchemy\storage\cache\Dummy;

class CacheTest extends PHPUnit_Framework_TestCase
{
    public function testAddDriver()
    {
        Cache::addDriver(new Dummy(), 'dummy1');
        Cache::addDriver(new Dummy(), 'dummy2');

        $this->assertInstanceOf('alchemy\storage\cache\Dummy', Cache::getDriver('dummy1'));
        $this->assertInstanceOf('alchemy\storage\cache\Dummy', Cache::getDriver('dummy2'));

    }

    public function testUseCache()
    {
        Cache::addDriver(new Dummy($this->cacheArray), 'dummy3');
        Cache::addDriver(new Dummy(), 'dummy1');
        Cache::addDriver(new Dummy(), 'dummy2');
        Cache::useDriver('dummy3');
        Cache::set($key = 'test_1', $value = 1);
        Cache::useDriver('dummy2');
        Cache::set($key = 'test_2', $value = 2);
        Cache::useDriver('dummy1');
        Cache::set($key = 'test_3', $value = 3);

        $this->assertTrue(isset($this->cacheArray['test_1']));
        $this->assertEquals($this->cacheArray['test_1'], 1);
        $this->assertFalse(isset($this->cacheArray['test_2']));
        $this->assertFalse(isset($this->cacheArray['test_3']));

    }

    public $cacheArray = array();
}