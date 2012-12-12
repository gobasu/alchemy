<?php
use alchemy\security\Acl;
use alchemy\security\acl\Role;
/**
 * AclTest
 */
class AclTest extends PHPUnit_Framework_TestCase
{
    public function testSetup()
    {
        Acl::setup();
        $this->assertTrue(Acl::hasRole(Acl::ACL_DEFAULT));
        $this->assertFalse(Acl::hasAccess('Some.action'));
    }


    public function testDefineRole()
    {

        Acl::defineRole(self::TEST_ROLE)->allow('*');

        $this->assertTrue(Acl::roleExists(self::TEST_ROLE));
    }

    public function testAddRole()
    {
        Acl::addRole(self::TEST_ROLE);

        $this->assertTrue(Acl::hasRole(self::TEST_ROLE));

        $this->assertTrue(Acl::hasAccess('Some.action'));
        $this->assertTrue(Acl::hasAccess('Some.*'));
        $this->assertTrue(Acl::hasAccess('*.action'));
        $this->assertTrue(Acl::hasAccess('*'));
    }

    public function testRemoveRole()
    {
        $this->assertTrue(Acl::hasAccess('Some.action'));
        $this->assertTrue(Acl::hasAccess('Some.*'));
        $this->assertTrue(Acl::hasAccess('*.action'));
        $this->assertTrue(Acl::hasAccess('*'));

        Acl::removeRole(self::TEST_ROLE);

        $this->assertTrue(Acl::hasRole(Acl::ACL_DEFAULT));
        $this->assertFalse(Acl::hasRole(self::TEST_ROLE));
        $this->assertFalse(Acl::hasAccess('Some.action'));
        $this->assertFalse(Acl::hasAccess('Some.*'));
        $this->assertFalse(Acl::hasAccess('*.action'));
        $this->assertFalse(Acl::hasAccess('*'));
    }

    public function testRemoveAllRoles()
    {
        Acl::addRole(self::TEST_ROLE);
        Acl::removeAllRoles();

        $roles = Acl::getAttachedRoles();
        $this->assertTrue(isset($roles[Acl::ACL_DEFAULT]));
        $this->assertEquals(count($roles), 1);
    }

    const TEST_ROLE = 'MyTestRole';
}
