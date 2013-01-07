Alchemy framework
=================

Fast and clean PHP micro framework to build not only websites. Alchemy focus to be simple and yet
gives you the way to build your application faster than writing from scratch. 

What differs this framework from others:
- It does not trying force on you to use predefined dir structure you may not like or don't want to have.
- It does not mixing framework files with your application files
- Say no to intricate configurations and setups, alchemy requires no configuration

Performance notes
-----------------
Tests were run at machine:
- Core 2 Duo 2.4 GHz
- 8GB RAM
- 120 GB SSD Intel
- PHP 5.4.4 + APC 
- XHProf

All values are average after 10 runs. More results will appear- stay tuned.

**Simple hello world page**

<pre>
+==============+==========+============+
|   Framework  | time[ms] | mem[bytes] |
+==============+==========+============+
|    alchemy   |  11,370  |  286,304   |
+--------------+----------+------------+
|     slim     |  23,125  |  449,280   |
+--------------+----------+------------+
| code igniter |  24,966  |  486,760   |
+--------------+----------+------------+
|     cake     |  818,488 | 2,943,936  |
+--------------+----------+------------+
|    laravel   |  99,838  | 1,385,688  |
+--------------+----------+------------+
</pre>

**Hello world with database handling**

**Page with acl usage**

**Plugin handling**

**Custom errorpage**



List of contents
----------------

**[Setup](#setup)**
- [Server Requirements](#server-requirements)
- [Basics](#basics)
- [Creating bootstrap file](#creating-bootstrap-file)

**[Routing](#routing)**
- [Resource](#resource)
- [Route types](#route-types)
- [Advanced routing](#advanced-routing)

**[Controllers](#controllers)**
- [Tying route to a controller](#tying-route-to-a-controller)
- [Getting route parameters](#getting-route-parameters)

**[Models](#models)**
- [Annotation system](#annotation-system)
- [Example model](#example-model)
- [Setting up database connection](#setting-up-database-connection)
- [Getting item by pk](#getting-item-by-pk)
- [Updating and creating model](#updating-and-creating-model)
- [Simple search API](#simple-search-api)
- [Custom queries](#custom-queries)

**[Views](#views)**

**[Event system](#event-system)**
- [Dispatching a custom event](#dispatching-a-custom-event)
- [Attaching listeners](#attaching-listeners)
- [About listeners](#about-listeners)
- [Framework events](#framework-events)

**[Session](#session)**
- [Using namespace](#using-namespace)
- [Custom session handler](#custom-session-handler)

**[Acl](#acl)**
- [Defining roles](#defining-roles)
- [Assigning roles](#assigning-roles)
- [Removing roles](#removing-roles)
- [Checking user's roles](#checking-users-roles)

**[I18n](#i18n)**
- [Accepting language from client headers](#accepting-language-from-client-headers)
- [Creating language's aliases](#creating-languages-aliases)

**[Image manipulation](#image-manipulation)**

**[Miscellaneous](#miscellaneous)**

Setup
=====

Server requirements
-------------------

- PHP 5.3.x or newer.
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

Right now alchemy supports only MySQL connections. If you need use other database
than MySQL you can always do it by yourself by implementing `alchemy\storage\db\IConnection`.

To setup connection to MySQL database you should use `alchemy\storage\DB` class and proper connection
class in your bootstrap file (or configuration you you've created one). Example below:

```php
<?php
require_once $VALID_PATH_TO . '/alchemy/app/Application.php';
use alchemy\app\Application;
use alchemy\storage\DB;
use alchemy\storage\db\connection\MySQL;

//setup connection here
DB::add(new MySQL('{host}','{username}','{password}','{dbname}'), $connectionName = DB::DEFAULT_NAME);

$app = new Application($PATH_TO_APPLICATION_ROOT);
//add routes here...
$app->run();
```

`$connectionName` allows you to use in your application multiple connections in one application.

Getting item by pk
------------------

To simply get item from storage by pk just use `Model::get()` function, e.g.

```php
$item = app\model\Product::get('{id here}');
// now if item exists you will get instance of a app\model\Product

```

Updating and creating model
---------------------------

To update or create item in database use `Model->save` method,
Updating record example below:
```php
$item = app\model\Product::get('{id here}');
try {
    $item->productLine = 'Motorcycles';
    $item->save();
} catch (\Exception $e) {
    //handle error when object is not saved
}
```

When creating new item in database make sure you have set in mysql
autoincrement and primary key on field marked as @Pk, otherwise you have
to provide a pk value, e.g.

```php
$item = new app\model\Product('AP-123');
$item->productLine = 'Motorcycles';
$item->productName = 'HB-123';
$item->buyPrice = 1120.12;

try {
    $item->save();
} catch (\Exception $e) {
  //handle error when object is not saved
}
```

Simple search API
-----------------

Alchemy provides simple search api through `Model::findOne()` and `Model::findAll()`
class' methods.
All you need to do is to put the query array, where array's key is the fieldName and
value is the searched value in DB. *Framework supports only simple search queries which
means all query terms must be met*

Let's assume we want to find all products in `product` table where `productLine` = `Motorcycles`

```php
$collection = \app\model\Product::findAll(array('productLine' => 'Motorcycles'));
echo $collection[0]->productName;//will display the first item product name
```

Of course you can also use `>` `<` `>=` `<=` operators in your query as well as array value to match
one of the predefined values, e.g.

```php
$collection = Product::findAll(array(
    'productLine' => array('Trucks and Buses', 'Planes'),
    'buyPrice <=' => 31
));
```

**Sorting example**
If you need to sort your simple search query you have to pass the second argument
to the `Model::findOne` or `Model::findAll` function, e.g

```php
$collection = \app\model\Product::findAll(array('productLine' => 'Motorcycles'), array('buyPrice' => 1);
echo $collection[0]->productName;//will display the first item product name
```

The query tells find all records in table `products` where `productLine => 'Motorcycles'` and sort
results in ascending order by column `buyPrice`

Custom queries
--------------

Of course simple search API will not satisfy your needs. To build custom query you should
define class's method in desired model and use the connection class for this. Please consider
followin example

```php
<?php
namespace app\model;
use alchemy\storage\db\Model;
/**
 * Product
 *
 * @Connection default
 * @Collection products
 * @Pk productCode
 */

class Product extends Model
{
    /**
     * Our custom query which will search for all products from
     * motorcycle's line
     */
    public static function getMotorcycles()
    {
        $schema = self::getSchema();
        $fieldList = '`' . implode('`,`', $schema->getPropertyList()) . '`';
        $sql = 'SELECT ' . $fieldList . '
            FROM ' . $schema->getCollectionName() . '
            WHERE productLine = "Motorcycles"';

        return self::getConnection()->query($sql, $schema);
    }

    /**
     * @Param(type=number, required=true)
     */
    protected $productCode;

    /**
     * @Param(type=string, required=false)
     */
    protected $productName;

    /**
     * @Param(type=date)
     */
    protected $productLine;

    /**
     * @Param(type=number)
     */
    protected $buyPrice = 0.00;
}
```

As you noticed we use method `self::getSchema()`. This function returns schema object
generated for our model please see the [`alchemy\storage\db\ISchema`](https://github.com/dkraczkowski/alchemy/blob/master/alchemy/storage/db/ISchema.php).

Gennerally `alchemy\storage\db\connection\MySQL` extends [`\PDO`](http://php.net/pdo) class so you
are propably familiar with this one already. The one difference is that `PDO->query` function is overridden
and returns set of model classes if query find something otherwise empty array will be returned
`alchemy\storage\db\connection\MySQL->query` accepts 3 parameters:
- `string` sql
- `alchemy\storage\db\ISchema` object
- `array` bind data (not required)

Views
=====

Alchemy for views uses [mustashe](#https://github.com/bobthecow/mustache.php) templating system. With small changes
instead `{{` & `}}` default tags are set to `<%` `%>`. And template dir is default set to `$PATH_TO_APPLICATION_ROOT/view`.
You can simply change it to anything you want by passing argument to `alchemy\ui\View` class' constructor.
More detailed info about mustashe can be found [here](https://github.com/bobthecow/mustache.php/wiki)

Event system
============

`alchemy\app\Application`, `alchemy\app\Controller`, `alchemy\storage\db\Model` extends `alchemy\event\EventDispatcher`.
The `alchemy\event\EventDispatcher` is a implementation of observer pattern. It allows you to take actions when event
is dispatched by given object as well as defining your own events. To get familiar with the framework's event system 
I recommand you to visit [event package](http://github.com/dkraczkowski/alchemy/tree/master/alchemy/event)

Dispatching a custom event
--------------------------

To define a event class you must extend `alchemy\event\Event` class and give it a meaningfull name. Let's assume 
we want to dispatch an event meaning that item was added to cart, e.g.
```php
<?php
namespace app\event;
use alchemy\event\Event;
class OnAddToCart extends Event {}
```

Now lets build our Cart model class:
```php
<?php
namespace app\model;
use alchemy\storage\db\Model;
use alchemy\event\Event;
/**
 * Very simple cart class implementation
 */
class Cart extends Model
{
    public function __construct($sessionId = null)
    {
        parent::__construct($sessionId);
    }
    public function addToCart($itemId, $count)
    {
        if (!isset($this->cartData[$itemId])) {
            $this->cartData[$itemId] = array(
                'itemId'    => $itemId,
                'count'     => 0,
            );
        }

        $this->cartData[$itemId]['count'] += $count;
        $this->lastInsertedItem = $itemId;
        
        //here is a dispatching event method
        $this->dispatch(new \app\event\OnAddToCart($this));
    }
    
    public function getLastInsertedItemId()
    {
        return $this->lastInsertedItem;
    }

    public function removeFromCart($itemId)
    {
        unset($this->cartData[$itemId]);
    }

    public function updateCart($itemId, $count)
    {
        $this->cartData[$itemId]['count'] = $count;
    }
    /**
     * Called when object is get from db
     */
    public function onGet()
    {
        $this->cartData = json_decode($this->cartData, true);
    }
    /**
     * Called when framework is trying to save object to DB
     */
    public function onSave()
    {
        $this->cartData = json_encode($this->cartData);
    }
    protectes $lastInsertedItem;
    protected $sessionKey;
    protected $cartData;
}
```
As you can see to dispatch ane event you need to use `$this->dispatch` method which accepts one parameter of `alchemy\event\Event` instance.
I have also used in the example some builded in model's methods to encode/decode cart data to json when it is needed. 
We will revisit framework events later on.

The `dispatch` function does two things:
- dispatches an event in object scope
- pass an event to global event hub (this is helpfull for plugins)

Attaching listeners
-------------------

You can attach listeners strict to an object or to the `alchemy\event\EventHub`. 

Attaching listener to an object:
```php
$cart = Cart::get($sessionId);
$cart->addListener('app\event\OnAddToCart', function($event){
  $id = $event->getCallee()->getLastInsertedItemId();
  echo 'Added to cart item with id:' . $id;
});
```

Attaching listener to EventHub:
```php
alchemy\event\EventHub::addListener('app\event\OnAddToCart', function($event){
  $id = $event->getCallee()->getLastInsertedItemId();
  echo 'Added to cart item with id:' . $id;
});
```

`alchemy\event\EventHub` is global event center- here goes all events dispatched by your controllers/models, so you can
simply extend/pluginize your application just by using this special class. 

You should also be aware of dispatching to many events from your controllers/models because it can influent on your application robustness.

About listeners
---------------

Listeners simply are php's callables, e.g.
- `array($object, 'method')`
- `array('Class', 'method')`
- `'functionname'`
- `closures`

In my examples I've used closures as event hanlders, you are not limited only to closures use whatever 
is a callable object.
You can also attach multiple different listeners to a desired event.

Framework events
----------------

Here is the list of major framework's events:
- `alchemy\app\event\OnError` dispatched when resource was executed with uncatched exceptions (you can use it to build your own error pages)
- `alchemy\app\event\OnShutdown` dispatched when application is going to finish the execution

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

I18n
====

*If you are not familiar with gettext I highly recommand you to read the [o'reilly article](http://onlamp.com/pub/a/php/2002/06/13/php.html) about gettext and php.*


You can simply implement i18n in your application with a little assist of alchemy's util tool.

Setting up the utility tool (*nix only environments)
--------------------------

To setup utility tool create link in you application dir, and run `locale:generate` eg
```
cd /path/to/your/application
ln -s /full/path/to/alchemy/dir/alchemy ./alchemy
./alchemy
                           Welcome to alchemy util toolset                      
                           ===============================                      
Command list:
  - application:create [name]   creates bootstrap application in current working directory
	- locale:generate             generates locale's template for current working directory

locale:generate
Are you sure you want to generate locale for application from current directory? [yes/no]y
Generating template...
...
Choose save file for locale template or hit enter [locale/template/locale.pot]
Locale template file saved!
quit
```

Now use poedit or other gettext catalog editor to edit the template file and save it eg. `locale\en\LC_MESSAGES\messages.po`
Poedit will authomatically create `*.mo` file.

The last thing you need to do is to create `alchemy\util\I18n` instance in your bootstrap file, eg

```php
<?php
require_once realpath(dirname(__FILE__) . '/../../alchemy/app/Application.php');

use alchemy\app\Application;

$app = new Application(realpath(dirname(__FILE__) . '/../'));
//important create instance after new Application
$i18n = new \alchemy\util\I18n();
$i18n->setLanguage('en');

$app->addRoute('*', 'example\controller\HelloWorld->sayHello'); //default route
$app->run();
```

Accepting language from client headers
-----------------------------------

If you need to automatically use language for user basing on client headers use `\alchemy\util\I18n::acceptFromHTTP` method, eg
```php
$i18n = new \alchemy\util\I18n();
$i18n->acceptFromHTTP();
```

Creating language's aliases
-------------------------

Using language based on client headers may force you to copy translations,eg. 
Let's assume you've got an `en` language support but browser headers are following
```
en-gb, en-us;q=0.7
```

So instead copying existing translation to `en_GB` or `en_US` dirs you can just use `\alchemy\util\I18n::addAlias($aliasName, $languageCode)`

```php
$i18n = new \alchemy\util\I18n();
$i18n->addAlias('en_GB', 'en');
$i18n->addAlias('en_US', 'en');
$i18n->acceptFromHTTP();
```

Image Manipulation
==================

Creating image handler class
----------------------------
```php
$img = new alchemy\file\Image($path, $preserveTransparency = true);
```
Parameters:
- *$path* image path
- *$preserveTransparency* tells if png/gif images should preserve their transparency

Getting width/height
--------------------
```php
$img->getWidth();
$img->getHeight();
```

Resizing
--------
```php
$img->resize($width, $height, $type = alchemy\file\Image::RESIZE_MAXIMAL);
```
Parameters:
- *$width* new image width
- *$height* new image height
- *$type* tells whatever image should use this values as minimal values `alchemy\file\Image::RESIZE_MINIMAL` or maximal `alchemy\file\Image::RESIZE_MAXIMAL`

Resizing only by `$height`
```php
$img->resize(null, 100);
```

Cropping image
--------------
```php
$img->crop($startX, $startY, $width, $height);
```
Parameters:
- *$startX* x position to start from
- *$startY* y position to start from
- *$width* new image width
- *$height* new image height

Cropping image from center
```php
$img->cropFromCenter($width, $height);
```
Parameters:
- *$width* new image width
- *$height* new image height

Rotating image
--------------
```php
$img->rotate($rotate = 'CW');
```
Parameters:
- *$rotate* can be `CW`(clock wise) or `CCW`(counter clock wise)

Saving file
-----------
```php
$img->save($compression = 100, $file = null);
```
Parameters:
- *$compression* 1-100 (higher - better)
- *$file* (not required) if passed will save as new file otherwise will try to override existing file

Miscellaneous
=============

Custom error handling
---------------------

If you need to build 404 page or custom page when error occurs in your application you can use `Application->onError`
method in you bootstrap file and pass a callable parameter.

```php
<?php
require_once $VALID_PATH_TO . '/alchemy/app/Application.php';
use alchemy\app\Application;

$app = new Application($PATH_TO_APPLICATION_ROOT);
/**
 * You can use here all callable resource, eg
 * Controller->method
 * Controller::method
 * array($object,'method')
 * array('Class','method')
 * closure
 */
$app->onError(function(\Exception $e){
    echo '404';
});
//add routes here...
$app->addRoute('*', function(){
  echo 'Hello World!';
});

//run application
$app->run();
```
