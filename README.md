Alchemy framework
=================

Fast and clean PHP micro framework to build not only websites. Alchemy focus to be simple and yet
gives you the way to build your application faster than writing from scratch. 

What differs this framework from others:
- It does not trying force on you to use predefined dir structure you may not like or don't want to have.
- It does not mixing framework files with your application files
- Say no to intricate configurations and setups, alchemy requires no configuration


List of contents
----------------

[Setup](#setup)
- [Server Requirements](#server-requirements)
- [Basics](#basics)
- [Creating bootstrap file](#creating-bootstrap-file)

[Routing](#routing)
- [Resource](#resource)
- [Route types](#route-types)
- [Advanced routing](#advanced-routing)

[Controllers](#controllers)
- [Tying route to a controller](#tying-route-to-a-controller)
- [Getting route parameters](#getting-route-parameters)

[Models](#models)
- [Annotation system](#annotation-system)
- [Example model](#example-model)

Setup
=====

Server requirements
-------------------

- PHP 5.4.x or newer.
- Curl extension on
- PDO with MySQL (to make DB working)

Basics
------

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
- Dirnames must be lower case
- Every file containing class which should be loaded dynamically must have the same name as the class. 
- The namespace of given class must corresponds to the dirname. 

Assume we willing to create `HelloWorld` class which will be one of controllers for our application, we should
end with path similar to: `/myapp/mycontroller/HelloWorld.php`, and therefore file must containing contents
similar to below ones

```php
<?php
namespace myapp\mycontroller;
class HelloWorld extends \alchemy\app\Controller
{
  //here goes your methods and properties
}
```

The other one is a framework package (the `alchemy` dir)- it holds all classes that simplify your work.
Framework has been sharded into packages, every package has its own role in framework;

- `app` FW's core classes which setup all application and controlls flow
- `event` event package wich uses Observer pattern to make framework elastic 
- `file` file manipullation classes (images, xls, etc... goes here)
- `http` classes connected with http protocol and request handling
- `object`
- `security` acl and validation class
- `storage` package which focuses on persisting data
- `ui` views and views helpers
- `vendor` vendor classes and external API helpers (paypal, payu, ups, facebook, g+, etc...)

Creating bootstrap file
-----------------------

In order to create your first application point you server's root directory to `public` dir and put there an `index.php` file

```php
<?php
require_once $VALID_PATH_TO . '/alchemy/app/Application.php';
use alchemy\app\Application;

$app = new Application($PATH_TO_APPLICATION_ROOT);

//add routes here...
$app->addRoute('*', function(){ 
  echo 'Hello World!';
});

//run application
$app->run();
```

`$PATH_TO_APPLICATION_ROOT` must be valid dirname pointing to the application root directory (the one that holds whole app's
direcotry structure)


Now if you go to `http://localhost/` url you should see:

    Hello World

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

Controllers
===========

Simply to create a controller you need to extend `alchemy\app\Controller` class in your controller directory.

**Controller Example**

```php
<?php
namespace app\controller;
use alchemy\app\Controller;
class HelloWorld extends Controller
{
    public function index()
    {
        return "Hello World";
    }
}
```

Tying route to a controller
---------------------------

Lets assume we want to see the result of `app\controller\HelloWorld->index()` when we go to `http://host/showhello`
Than all you need it to put in your bootstrap file after creating instance of `alchemy\app\Application` following
line of code
```php
$app->addRoute('/showhello', 'app\controller\HelloWorld->index');
```

Getting route parameters
------------------------

Sometimes you need route that can provide some additional info for resource (like id). 
Lets assume we have following url `http://host/item/get/12`
And we need to point it to `app\controller\Item->get` and pass number `12` as a parameter.
Consider following example:
```php
//put this route in your bootstrap
$app->addRoute('/item/get/{$id}', 'app\controller\Item->get');
```

```php
<?php
//controller code
namespace app\controller;
use alchemy\app\Controller;
class Item extends Controller
{
    public function get($params)
    {
        echo "The passed id equals:" . $params['id'];
    }
}
```
Keep in mind you shouldn't trust this data, always validate/escape to prevent from harming your application.

Models
======

Alchemy has its own approach to models. Generally speaking alchemy's model is a mixin of entity and collection,
therefore collection methods like aggregating data should be defined as class methods but entity's methods should
be defined as a object's methods in the same class. 

For every model's class framework dynamically creates schema class based on annotations used in model.

Annotation system
-----------------
Annotations provide data about a program that is not part of the program itself. 
They have no direct effect on the operation of the code they annotate. 

Model annotations are split into two types:
- class's annotations 
  - `@Connection` (not required, defaul value is 'default')
  - `@Collection` (required)
  - `@Pk` (required)
- property's annotations
  - `@Param` (required)
  - `@Validator` (not required) 

**`@Connection`**

Tells which connection should be used in given model class, e.g.

    @Connection my_connection
    
Default value is: `default`

**`@Collection`**

If you are using MySQL or other PDO based connection class this means which table should be used
for persisting your data, e.g.

    @Collection SQLTableName

This field is required and has no default value

**`@Pk`**

This tells which field should be used in get, save, delete operations, e.g.

    @Pk Id
    
This field is required and has no default value

**`@Param`**

Used to inform schema that this field is integral part of data used by connection class.
You need to setup this annotation on object's propery. 
This annotation has two attributes:
- `type` tells what data type property holds (avaible: number, string, data, boolean, blob, enum)
- `required` tells whatever the value must be set or not 
  
Example:

    @Param(type=string, required=false)
    
Example model
-------------
Lets assume we have got sql table `product`:
<pre>
+-------------+-------------+-------------+-------+
| productCode | productName | productLine | price |
+-------------+-------------+-------------+-------+
|             table data goes here                |
+-------------------------------------------------+
</pre>

Example database you can grab from [here](http://www.mysqltutorial.org/_sites/mysqltutorial.org/DownloadHandler.ashx?pg=b98d7eb2-72ba-4a90-9d91-d80c81c2e6dc&section=66d3abc9-1984-4ae0-96e1-ab685cce002c&file=sampledatabase.zip)

Our model will look similar to:
```php
<?php
namespace app\model;
use alchemy\storage\db\Model;
/**
 * Simple product model
 * Below we will set required annotations
 * Our sql table is 'product' so @Collection must
 * be set to 'produt' and @Pk to 'productCode'
 *
 * We will not set any @Connection here we just use 
 * default one
 *
 * @Collection products
 * @Pk productCode
 */

class Product extends Model
{
    /**
     * @Param(type=string)
     */
    protected $productCode;

    /**
     * @Param(type=string)
     */
    protected $productName;

    /**
     * @Param(type=string)
     */
    protected $productLine;

    /**
     * @Param(type=number)
     */
    protected $buyPrice = 0.00;
}
```

Setting up database connection
------------------------------

Right now alchemy supports only MySQL connections. To setup connection to MySQL
database you should use `alchemy\storage\db` and proper connection class in your
bootstrap file. Example below:

```php
<?php
require_once $VALID_PATH_TO . '/alchemy/app/Application.php';
use alchemy\app\Application;
use alchemy\storage\DB;
use alchemy\storage\db\connection\MySQL;

//setup connection here
DB::add(new MySQL('{host}','{username}','{password}','{dbname}'));

$app = new Application($PATH_TO_APPLICATION_ROOT);
//add routes here...
$app->run();
```
If you need use other database than MySQL you can always do it by yourself by
implementing `alchemy\storage\db\IConnection`

Getting item by pk
------------------

To simply get item from storage by pk just use `Model::get()` function, e.g.

```php
<?php
namespace app\controller;
use alchemy\app\Controller;
use app\model\Product;
class DBExample extends Controller
{
    public function index()
    {
        $item = Product::get('{id here}');
        // now if item exists you will get instance of a app\model\Product
    }
}
```

