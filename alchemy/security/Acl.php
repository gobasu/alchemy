<?php
namespace alchemy\security;
use alchemy\security\acl\Role;
use alchemy\storage\Session;
class AclException extends \Exception {}
class AccessDeniedException extends AclException {}
class Acl
{
    /**
     * Defines role to make it usable by addRole and removeRole
     *
     * @param $name role name
     * @return Role
     */
    public static function defineRole($name)
    {
        return self::$definedRoles[$name] = new Role();
    }

    /**
     * Adds role to current user's role list
     * @param $name previously defined role name
     */
    public static function addRole($name)
    {
        if (!self::roleExists($name)) return false;
        self::$cache = array();
        self::$attachedRoles[$name] = $name;
        return true;
    }

    /**
     * Gets attached roles
     * @return array
     */
    public static function getAttachedRoles()
    {
        return self::$attachedRoles;
    }

    /**
     * Removes role from current user's role list
     * @param $name previously defined role name
     */
    public static function removeRole($name)
    {
        if (self::hasRole($name))
        {
            self::$cache = array();
            unset(self::$attachedRoles[$name]);
        }
    }

    /**
     * Checks if user has given role in their role list
     *
     * @param $name previously defined role name
     * @return bool true if user has the role otherwise false
     */
    public static function hasRole($name)
    {
        return isset(self::$attachedRoles[$name]);
    }

    /**
     * Checks whatever role was defined or not
     *
     * @param $name role name
     * @return bool true if role is defined otherwise false
     */
    public static function roleExists($name)
    {
        return isset(self::$definedRoles[$name]);
    }

    /**
     * Checks whatever user has access to passed resource
     * @return bool
     */
    public static function hasAccess($resource)
    {
        //if (key_exists($key, self::$cache)) return self::$cache[$key];

        $access = false;

        foreach (self::$attachedRoles as $role)
        {
            $role = self::$definedRoles[$role];
            //var_dump($role);
            if (!$role->hasAccess($resource)) continue;
            $access = true;
            break;
        }

        self::$cache[$resource] = $access;
        return $access;
    }

    /**
     * Remove all attached roles except default one
     */
    public static function removeAllRoles()
    {
        self::$attachedRoles = array();
        self::$cache = array();
        self::addRole(self::ACL_DEFAULT);
    }

    /**
     *
     * @return Role
     */
    public static function defineDefaultRole()
    {
        return self::defineRole(self::ACL_DEFAULT);
    }

    public static function setup()
    {
        $acl = Session::get('acl');
        self::$attachedRoles = &$acl['user_roles'];
        self::$cache = &$acl['cache'];

        if (!count(self::$attachedRoles))
        {
            self::$attachedRoles = array();
            //define default role if not defined
            if (!self::roleExists(self::ACL_DEFAULT)) {
                self::defineRole(self::ACL_DEFAULT)->deny('*');
            }

            self::addRole(self::ACL_DEFAULT);
        }

        if (!self::$cache) self::$cache = array();
    }

    const ACL_DEFAULT = 'DefaultRole';

    private static $attachedRoles = array();
    private static $definedRoles = array();
    private static $cache = array();
}