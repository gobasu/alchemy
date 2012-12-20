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
        $this->assertTrue(Acl::hasRole(Acl::ACL_DEFAULT));
        $this->assertFalse(Acl::isAllowed('Some.action'));
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

        $this->assertTrue(Acl::isAllowed('Some.action'));
        $this->assertTrue(Acl::isAllowed('Some.*'));
        $this->assertTrue(Acl::isAllowed('*.action'));
        $this->assertTrue(Acl::isAllowed('*'));
    }

    public function testRemoveRole()
    {
        $this->assertTrue(Acl::isAllowed('Some.action'));
        $this->assertTrue(Acl::isAllowed('Some.*'));
        $this->assertTrue(Acl::isAllowed('*.action'));
        $this->assertTrue(Acl::isAllowed('*'));

        Acl::removeRole(self::TEST_ROLE);

        $this->assertTrue(Acl::hasRole(Acl::ACL_DEFAULT));
        $this->assertFalse(Acl::hasRole(self::TEST_ROLE));
        $this->assertFalse(Acl::isAllowed('Some.action'));
        $this->assertFalse(Acl::isAllowed('Some.*'));
        $this->assertFalse(Acl::isAllowed('*.action'));
        $this->assertFalse(Acl::isAllowed('*'));
    }

    public function testRemoveAllRoles()
    {
        Acl::addRole(self::TEST_ROLE);
        Acl::forget();

        $roles = Acl::getRoles();
        $this->assertTrue(isset($roles[Acl::ACL_DEFAULT]));
        $this->assertEquals(count($roles), 1);
    }

    const TEST_ROLE = 'MyTestRole';
}
