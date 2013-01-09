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
