Routing
=======
Router allows you to redirect url that match the route's expression to desired application's resource (method, closure and so).
All routes should be defined in your bootstrap file or configuration where `alchemy\app\Application` instance is avaible.

Resource
--------

Each route need to point to a specific resource (closure function, class' method, object's method)
Framework supports three variations of resources:
- closures, eg:

        $app->addRoute('*', function(){
            //handle request here
        });

- class' method, eg:

        $app->addRoute('*', 'your\controller\MyController::index');

- object's method, eg:

        $app->addRoute('*', 'your\controller\MyController->index');

The difference between using class' method (`->`) and object's method (`::`) is when using `->`
framework will automaticaly create an instance of given class otherwise it will just call the method.

Basic routing example
---------------------

```php
use alchemy\app\Application;
$app = new Application($appDir);
$app->addRoute('hello/world', 'controller\Hello::worldMethod');
```  

Dynamic routing example
-----------------------
```php
use alchemy\app\Application;
$app = new Application($appDir);
$app->addRoute('/{$controller}/{$method}', 'app\controller\{$controller}->{$method}');
```

Using different HTTP methods
----------------------------

```php
use alchemy\app\Application;
$app = new Application($appDir);
$app->addRoute('GET /{$controller}/{$method}', 'app\controller\{$controller}->{$method}');
$app->addRoute('POST /{$controller}/{$method}', 'app\controller\PostHandler->{$controller}{$method}');
```