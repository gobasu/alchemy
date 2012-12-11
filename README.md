alchemy
=======

Fast and clean PHP micro framework to build websites and not only. Alchemy focus to be simple and yet
gives you the way to build your application faster than writing from scratch.
Alchemy mainly focuses to be usefull on handling and processing the requests. The main application
flow is:
- register rewrites in router
- build the http request
- pass request to the router
- gently handle request and find out the matching controller
- load it and fire right method
- return Response object

Server requirements
===================

- PHP 5.4.x or newer.
- Curl extension on
- PDO with MySQL (to make DB working)

Installation
============

In the repository there are two folders one of them is named `app` and this is example structure
for your application code.
Dir structure looks like this:
- `cache`
- `controller`
- `model`
- `view`
- `plugins` (not required)
- `public` (server's root directory have to point at this one)

Of course you can use totally different structure but you should follow some conventions:
- dirnames must be lower case
- every file in given dir which contains class which should be loaded dynamically by framework
must have the same name as the class. The namespace of given class must corresponds to the dirname. 
Assume we need to create `HelloWorld` class which will be one of controllers for our application, we should
end with path similar to this one: `/myapp/something/mycontroller/HelloWorld.php`, and file containing contents
below

```php
<?php
namespace myapp\something\mycontroller;
class HelloWorld extends \alchemy\app\Controller
{
  //here goes your methods and properties
}
```

The other one is a framework package (the `alchemy` dir)- it holds all classes that simplify your work.

Creating bootstrap file
-----------------------

In order to create your first application point you server's root directory to `public` dir and put there an `index.php` file

```php
<?php
require_once $VALID_PATH_TO . '/alchemy/app/Application.php';
use alchemy\app\Application;
$app = new Application($PATH_TO_APPLICATION_ROOT);
//add wildcard route
$app->addRoute('*', function(){ 
  echo 'Hello World!';
});
```

Now if you go to `http://localhost/` url you should see:

    Hello World

Routing
-------

**Resource**

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

**Types**

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




