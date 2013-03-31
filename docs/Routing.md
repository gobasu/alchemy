Routing
=======
Router allows you to redirect url that match the route's expression to desired application's resource (method, closure and so).
All routes should be defined in your bootstrap file or configuration where `alchemy\app\Application` instance is avaible.

Resource
--------

Each route need to point to a specific resource (closure function, class' method, object's method)
Framework supports three variations of resources:
- closures, eg:

        $app->onURI('*', function(){
            //handle request here
        });

- class' method, eg:

        $app->onURI('*', 'your\controller\MyController::index');

- object's method, eg:

        $app->onURI('*', 'your\controller\MyController->index');

The difference between using class' method (`->`) and object's method (`::`) is when using `->`
framework will automaticaly create an instance of given class otherwise it will just call the method.

Basic routing example
---------------------

```php
use alchemy\app\Application;
$app = Application::instance();
$app->setApplicationDir($PATH_TO_APPLICATION_ROOT);
$app->onURI('hello/world', 'controller\Hello::worldMethod');
```  

Dynamic routing example
-----------------------
```php
use alchemy\app\Application;
$app = Application::instance();
$app->setApplicationDir($PATH_TO_APPLICATION_ROOT);
$app->onURI('/{$controller}/{$method}', 'app\controller\{$controller}->{$method}');
```

Using different HTTP methods
----------------------------

```php
use alchemy\app\Application;
$app = Application::instance();
$app->setApplicationDir($PATH_TO_APPLICATION_ROOT);
$app->onURI('GET /{$controller}/{$method}', 'app\controller\{$controller}->{$method}');
$app->onURI('POST /{$controller}/{$method}', 'app\controller\PostHandler->{$controller}{$method}');
```
