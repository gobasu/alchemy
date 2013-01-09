Session
=======

###Starting session

If you are using `alchemy\app\Application::run` session will be started automatically for you otherwise to start using the
session you have to call:
```php
alchemy\storage\Session::start();
```

###Destroying session

```php
alchemy\storage\Session::destroy();
```

Using namespace
---------------

Alchemy's session is based on namespaces so before start using the session you need to get namespace. If namespace does not
exists session class will create it for you otherwise the existing one will be returned.

###Getting/creating namespace

```php
$namespace = alchemy\storage\Session::get('myNamespace');
$namespace->a++;//will increase the counter
$namespace['a']++;//you can also use a namespace like an array
echo $namespace->a;
```

###Setting expiration for the namespace

```php
$namespace = alchemy\storage\Session::get('myNamespace');
$namespace->setExpiration(10);//will expire in 10 seconds of idle
```

###Checking for expired session

```php
$namespace = alchemy\storage\Session::get('myNamespace');

//checks if session is expired
if ($namespace->isExpired()) {
    echo 'Session expired';
}
$namespace->setExpiration(10);
```
*Use `alchemy\storage\session\SessionNamespace->isExpired()` before `alchemy\storage\SessionNamespace->setExpiration()`
because `setExpiration()` will renew the session namespace expiration date.

Custom session handler
----------------------

If you would like to implement your own session handler please check the `alchemy\storage\session\IHander` for the implementation and before `Session::start()` call the
`alchemy\storage\Session::setHandler()` to use your custom session handler.

```php
alchemy\storage\Session::setHandler(new MyCustomSessionHandler());
```
