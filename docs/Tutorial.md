Tutorial purpose
-----------------

I will not cover all the topics here (html, css, js). This tutorial purpose is to show you some basics of
alchemy framework.

If you willing see the ready example's source go [here](https://raw.github.com/dkraczkowski/alchemy/master/examples/recipes)

What will we do
--------------

We will create here simple recipe book application with possibility to adding and removing recipes.
![Recipe book](https://raw.github.com/dkraczkowski/alchemy/master/docs/tut_ready.png)

I assume
-----------

You have got:
 - apache with rewrite mod on
 - php >= 5.3.x
 - php pdo with sqlite on
 - basic php, html, css, js knowledge

And you application's domain is `http://localhost` as well as apache's root dir of the host is set to
`path/to/app/public/`

Let's start
----------

First we will create our application's dir structure similar to this one:

- `app` (root folder)
    - `controller`
    - `data` (here we will keep our sqlite database)
    - `model`
    - `public`
    - `template`
        - `cache` (template's cache dir)
    - `view`


Now in `public` dir let's create our bootstrap file `index.php`

```php
<?php
   //require alchemy application
   require_once realpath(dirname(__FILE__) . '/../../../src/alchemy/app/Application.php');
   use alchemy\app\Application;

   //initialize application and set application DIR
   //this two lines are crucial
   $app = Application::instance();
   $app->setApplicationDir(realpath(dirname(__FILE__) . '/../'));

   //run application
   $app->run();
```

And .htaccess file

```
RewriteEngine On
RewriteBase /
RewriteRule ^index\.php$ - [L]
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule . /index.php [L]
```

The .htaccess file will forward all requests to `index.php`

If you run your application right now you should see error, similar to this one:

    Fatal error: Uncaught exception 'alchemy\app\ApplicationResourceNotFoundException' with message 'No callable resource found' in...

This means our framework hasn't found any controller which will handle the request.

First controller
---------------
Now go to the `controller` dir and create two files `BaseController.php` and `Page.php`
Our BaseController will be responsible for setup the database if it does not exists. Page controller will be responsible for
displaying error page, index page and so on.

Put to the BaseController folowing code:
```php
<?php
namespace app\controller;
use alchemy\app\Controller;

class BaseController extends Controller
{
    public function onLoad()
    {
    }
}
```
Right now it does nothing special but we will take care about it later. Now go to the `Page.php` and write this code:
```php
<?php
namespace app\controller;

class Page extends BaseController
{

    /**
     * Catches errors
     */
    public function errorAction(\Exception $e)
    {

    }

    /**
     * Displays default page
     */
    public function indexAction()
    {

    }

    /**
     * Adds new recipe
     */
    public function addrecipeAction()
    {

    }

    /**
     * Deletes recipe
     */
    public function deleterecipeAction($data)
    {
    }

    /**
     * Redirects to default page
     */
    protected function getIndex()
    {
        header('Location: /');
        exit();
    }

}
```
More about controllers you will find [here](/docs/Controllers.md)

Base template file and base view
--------------------------------

Now go to the `template` directory and create file `html.html`. This will be our base template file- it will be used by
rest of the templates.
```html
<!DOCTYPE html>
<html>
<head>
    <link href="http://netdna.bootstrapcdn.com/twitter-bootstrap/2.3.1/css/bootstrap-combined.min.css" rel="stylesheet" type="text/css">
    <link href="http://ivaynberg.github.io/select2/select2-3.3.2/select2.css" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script type="text/javascript"  src="http://netdna.bootstrapcdn.com/twitter-bootstrap/2.3.1/js/bootstrap.min.js"></script>
    <script type="text/javascript"  src="http://ivaynberg.github.io/select2/select2-3.3.2/select2.js"></script>
    <base href="http://alchemy">
    {% block head %}
    {% endblock %}
</head>
<body>
    {% block body %}
    {% endblock %}
</body>
</html>
```
I used here couple of external libraries (bootstrap, jquery, select2- you propably know them already).
As you can see I am using here some template engine.Alchemy has its own templating engine called `Mixture`. More about mixture you can find [here](/docs/Views.md).
Of course you are not obligated to use this template engine if you would like you can use here: smarty, mustache, twig or other but note that this tutorial will cover
only case for the Mixture.

Mixture like html has its tags and they starts with `{%` and ends with `%}`. Every tag has got name and can contain parameters so `{% block body %}` is a block tag with
parameter `body`. This defines block wich we will be using to extend this template. You will understand it as soon as we go further.

Now go to the `view` directory and create `BaseView.php` file with following content:
```php
<?php
namespace app\view;

use alchemy\app\View;
//use Mixture template engine
use alchemy\template\Mixture;

abstract class BaseView extends View
{
    public function __construct()
    {
        //helper for dynamically nested views
        //load and display view
        Mixture::addHelper('view', function($viewClass){
            try {
                if (class_exists($viewClass)) {
                    $viewObject = new $viewClass;
                    echo $viewObject;
                } else {
                    throw new \Exception();
                }
            } catch (\Exception $e) {
                echo '<div class="alert">
                    <strong>Warning!</strong> Trying to load non existing view class: ' . $viewClass . '
                </div>';
            }
        });

        //helper for displaying php styled variable dump
        Mixture::addHelper('pre', function($var){
            echo '<pre>';
            print_r($var);
            echo '</pre>';
        });

        //create template object, set template and cache dir for it
        $this->template = new Mixture(realpath(__DIR__ . '/../template'));
        $this->template->setCacheDir(realpath(__DIR__ . '/../template/cache'));
    }

    /**
     * @var Mixture
     */
    protected $template;
}
```

`Mixture::addHelper` extends Mixture engine with custom tags.
We've added here two helpers:
    - `view` this gives possiblity to use one view in another (this is pretty simple and powerfull solution)
    - `pre` for our debugging purpose

From now on every View class will have its own template rendering engine.

First page
----------

Okey. We 've got all set up. Now we can start write things which will actually displaying something.
Maybe let's start with error page- because this one you will see frequently while developing the application.

Go to the `template` dir and use the following html code:
```html
{% extends html.html%}
{% block head %}
 <title>Error!</title>
{% endblock %}
{% block body %}
 <div  class="alert alert-error" style="margin: 50px">
     <h4>Error!</h4>
     <br>
     {% pre $exception %}
 </div>
{% endblock %}

```

First line tells Mixture to extend base template file `html.html` so when this gets rendered it will output full html page.
Next lines simply replaces previously declared blocks. The {% pre $exception %} simply uses our custom helper to pretty print
exception object.

Got to the `view` directory and create `Error.php` file. This will be our error view class
```php
<?php
namespace app\view;
use alchemy\template\Mixture;

class Error extends BaseView
{
    public function render()
    {
        return $this->template->render('error.html', $this->vars);
    }
}
```

Nothing fancy here. Just tell Mixture which file should be used and pass internal `$this->vars` array to the template file.
Note: every view has to implement `render` method.

Now go to the `Page.php` and write `errorAction` method:
```php
public function errorAction(\Exception $e)
{
    $view = new ErrorView();
    $view->exception = $e;
    echo $view;
}
```

Last step is to modify a little our `public/index.php` file. We need to tell our Application where the error handler is located. Put the followin line after instanciating the Application class

    $app->onError('app\controller\Page->errorAction');

Now our `index.php` file should look like
```php
<?php
//require alchemy application
require_once realpath(dirname(__FILE__) . '/../../../src/alchemy/app/Application.php');
use alchemy\app\Application;
use alchemy\storage\DB;
use alchemy\storage\db\connection\SQLite;

//initialize application and set application DIR
//this two lines are crucial
$app = Application::instance();
$app->setApplicationDir(realpath(dirname(__FILE__) . '/../'));

//handle error pages by controller
$app->onError('app\controller\Page->errorAction');

//run application
$app->run();
```

Now if you run `http://localhost` you should something similar to:
![Error page](https://raw.github.com/dkraczkowski/alchemy/master/docs/tut_error.png)

Database setup
--------------
To use database we need to setup it first. Lets first create model wich will setup base structure of the database.
Go to the `model` dir and create file `Setup.php`
```php
<?php
namespace app\model;
use alchemy\storage\db\Model;

class SetupException extends \Exception {}

/**
 * Class Setup
 * @package app\model
 * @collection virtual
 */
class Setup extends Model
{
    /**
     * Builds the structure of recipes book database
     */
    public static function buildDatabase()
    {
        if (!self::query('CREATE TABLE IF NOT EXISTS recipe(
            recipe_id INTEGER PRIMARY KEY AUTOINCREMENT,
            title VARCHAR(255),
            description TEXT,
            created_on INTEGER
        )')) {
            throw new SetupException('COULD NOT CREATE TABLE: recipe');
        }

        if (!self::query('CREATE TABLE IF NOT EXISTS ingredient(
            ingredient_id INTEGER PRIMARY KEY AUTOINCREMENT,
            title VARCHAR(255)
        )')) {
            throw new SetupException('COULD NOT CREATE TABLE: ingredient');
        }


        if (!self::query('CREATE TABLE IF NOT EXISTS recipe_has_ingredient(
            recipe_has_igrendient_id INTEGER PRIMARY KEY AUTOINCREMENT,
            recipe_id INTEGER REFERENCES recipe(recipe_id) ON DELETE CASCADE,
            ingredient_id INTEGER REFERENCES ingredient(ingredient_id) ON DELETE CASCADE
        )')) {
            throw new SetupException('COULD NOT CREATE TABLE: recipe_has_ingredient');
        }
    }
}
```
We will use here alchemy's models. More info about alchemy's models you can get [here](/docs/Models.md)
To do this we need to extend `alchemy\storage\db\Model` class and append some phpdoc.
Probably you are asking yourself what the `@collection` does and why it is set to `virtual`.
`@collection` annotation tells alchemy's ORM to which table given model responds, here we will not use CRUD functionality that's why it is
set to virtual.

`alchemy\storage\db\Model::query` allows programmer to do direct queries to database we will use this functionality to create database's schema.

Our schema looks like this:
    - recipe
        * recipe_id
        * title
        * description
        * created_on
    - ingredient
        * ingredient_id
        * title
    - recipe_has_ingredient
        * recipe_has_ingredient_id
        * recipe_id
        * ingredient_id

`recipe` - table containing our recipes
`ingredient` - table containing all used ingredients
`recipe_has_ingredient` - many to many relation between recipes and ingredients.

Okie dokie ladies and gentleman. The last thing to move our database and whole setup process is again to modify our `index.php` file as well as some others classes.
Let's go to `index.php` and put database credintals before `Application::instance`

```php
<?php
//require alchemy application
require_once realpath(dirname(__FILE__) . '/../../../src/alchemy/app/Application.php');
use alchemy\app\Application;
use alchemy\storage\DB;
use alchemy\storage\db\connection\SQLite;

//define db path
define('DB_PATH', realpath(__DIR__ . '/../data') . '/recipies.db');

//check if database exists
//if not simply define RUN_SETUP for BaseController
if (!file_exists(DB_PATH)) {
    define('RUN_SETUP', true);
}

//use sqlite
DB::add(new SQLite(DB_PATH));

//initialize application and set application DIR
//this two lines are crucial
$app = Application::instance();
$app->setApplicationDir(realpath(dirname(__FILE__) . '/../'));

//handle error pages by controller
$app->onError('app\controller\Page->errorAction');

//handle default url
$app->onURI('*', 'app\controller\Page->indexAction');

//run application
$app->run();
```

Now our `index.php` file is almost done. Notice I've also addded additional line nothing to do with database
    $app->onURI('*', 'app\controller\Page->indexAction');

This one created default route for all request to Page controller. More about routes you can read [here](/docs/Routing.md)

Now go to the `BaseController.php` and change it to:
```php
<?php
namespace app\controller;

use alchemy\app\Controller;
use app\model\Setup;

class BaseController extends Controller
{
    public function onLoad()
    {
        if (defined('RUN_SETUP')) {
            Setup::buildDatabase();
        }
    }
}
```

Right now everything is set:).

More models
-----------

We need two models for our simple application:
    - `Recipe`
    - `Ingredient`

Let's create them
[`Recipe.php`](/examples/recipies/model/Recipe.php)
[`Ingredient.php`](/examples/recipies/model/Ingredient.php)

Alchemy's ORM does not provides relation support in direct way. But we can simply program it in clever way instead
sending thousands sqls to the database or consuming lot of cpu and memory.
In this task alchemy is very helpfull. Lest see what happens in each of methods

- `onLoad` method load all data from table `recipe_has_ingredient` and put its into class' array to use it later on
- `onGet` is a internal alchemy's ORM method it is called every time record is loaded from database it uses earlier created array to simulate many-to-many relation
- `addExistingIngredient` as name says its adds ingredient to the model
- `ingredientNamesList` outputs ingredients names separeted by semicolons
- `saveIngredients` saves recipe's ingredients to the databse (created new ingredient or simply add relation)

`@param` annotation tells ORM which object's variables should be treated as a table's columns (note that their names should be the same)