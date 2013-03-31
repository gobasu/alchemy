<?php
use alchemy\storage\Session;

class SessionTest extends PHPUnit_Framework_TestCase
{
    public function testStart()
    {
        $this->assertTrue(Session::start());
        $this->assertTrue(Session::isActive());
    }
    public function testGetID()
    {
        $this->assertNotNull(Session::getID());
    }

    public function testSetID()
    {
        $id = 'UnitTest';
        Session::setID($id);
        $this->assertEquals($id, Session::getID());
    }

    public function testGet()
    {
        $namespace = &Session::get('UnitTest');
        $this->assertInstanceOf('alchemy\storage\session\SessionNamespace', $namespace);
    }
}
