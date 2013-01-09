Routing
=======

Resource
--------

Each route need to point to a specific resource (closure function, class' method, object's method)
Framework supports three variations of resources
- closures, eg:

        $app->addRoute('*', function(){
            //handle request here
        });

- class' method, eg:

        $app->addRoute('*', 'your\controller\MyController::index');

- object's method, eg:

        $app->addRoute('*', 'your\controller\MyController->index');

The difference between using class' method and object's method is when you are using operator `->`
framework will automaticaly create an instance of given class and call a method. Otherhand if you use `::` operator
framework will search for a static method and instead creating an instace it will just call that method

Route Types
-----------

Alchemy supports to types of routing:
- static
- dynamic

Static routing means you point given uri to desired resource;

- Closure example:
```php
$app->addRoute('hello/world', function(){
    echo 'Hello World!';
});
```
- Object's method example
```php
$app->addRoute('hello/world', 'app\controller\Hello->world');
```
- Class' method example
```php
$app->addRoute('hello/world', 'app\controller\Hello::world');
```

Dynamic routing allows you to dynamically point to resource, lets asume we are willing to handle
various methods on a one object, so we can build route like:
```php
$app->addRoute('/{$controller}/{$method}', 'app\controller\{$controller}->{$method}');
```
Right now if someone goes to `http://localhost/world/hello` the `app\controller\World->hello` resource
will be executed if exists.

Advanced routing
----------------

You can define various resources to be executed by various request types like GET, POST, PUT, DELETE
Just simply put the request type before URI path, for example:
```php
$app->addRoute('GET /{$controller}/{$method}', 'app\controller\{$controller}->{$method}');
$app->addRoute('POST /{$controller}/{$method}', 'app\controller\PostHandler->{$controller}{$method}');
```