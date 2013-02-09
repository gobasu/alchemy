<?php
use alchemy\storage\Session;

class SessionNamespaceTest extends PHPUnit_Framework_TestCase
{
    public function testNew()
    {
        $namespace = &Session::get(self::$ns);
        $this->assertSame($namespace, $_SESSION[self::$ns]);
    }

    /**
     * @depends testNew
     */
    public function testSetGet()
    {
        $ns = &Session::get(self::$ns);
        $ns->test = 1;
        $ns['test2'] = 2;
        $this->assertEquals($ns->test, 1);
        $this->assertEquals($ns['test'], 1);
        $this->assertEquals($ns->test2, 2);
        $this->assertEquals($ns['test2'], 2);
        $this->assertTrue(isset($ns->test));
        $this->assertTrue(isset($ns['test2']));
    }

    /**
     * @depends testSetGet
     */
    public function testExpiration()
    {
        $ns = &Session::get(self::$ns);
        $this->assertFalse($ns->isExpired());
        $ns->setExpiration(-1);
        $this->assertTrue($ns->isExpired());

        $this->assertNull($ns->test);
        $this->assertNull($ns['test2']);
        $this->assertFalse(isset($ns->test));
        $this->assertFalse(isset($ns['test2']));
        $ns->setExpiration(0);
        $this->testSetGet();
    }

    public function testSleepWakeUp()
    {
        $ns = &Session::get(self::$ns);
        $ns = serialize($ns);
        $ns = unserialize($ns);
        $this->assertEquals($ns->test, 1);
        $this->assertEquals($ns['test'], 1);
        $this->assertEquals($ns->test2, 2);
        $this->assertEquals($ns['test2'], 2);
        $this->assertTrue(isset($ns->test));
        $this->assertTrue(isset($ns['test2']));

    }



    protected static $ns = 'UnitTest';
}
