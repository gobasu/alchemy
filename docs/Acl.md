Acl
===

Acl is a library which helps you simplify doing the multi-level authorization.
All you need is:
- define your system roles
- assign role to user
- check if user is allowed to access given resource

Defining roles
--------------

To define a role we will use `alchemy\security\Acl::defineRole($name)`, e.g.:
```php
Acl::defineRole('root')->allow('*'); //allow everything
Acl::defineRole('user')->allow('account.login'); //allow onlylogging in
Acl::defineRole('logged_in')->allow('account.*')->allow('history.*');
```

To define default role which will be assigned to user by default, use `alchemy\secutiry\Acl::defineRole()`, e.g.:
```php
Acl::defineRole()->allow('account.login');
```

Assigning roles
---------------

To assign role to user you must first define the role, and than use `alchemy\security\Acl::addRole($name)`, e.g.:

```php
Acl::addRole('user');
Acl::addRole('logged_in');
```

Removing roles
--------------

To remove one role use `alchemy\security\Acl::removeRole()`, to remove all roles use `alchemy\security\Acl::forget()`

Checking user's roles
-------------------

If you need to know wich roles are assigned to user use `alchemy\security\Acl::getRoles()`
