Tutorial purpose
-----------------

I will not cover all the topics here (html, css, js). This tutorial purpose is to show you some basics of
alchemy framework.

If you willing see the ready example's source go [here](https://raw.github.com/dkraczkowski/alchemy/master/examples/recipes)

What will we do
--------------

We will create here simple recipe book application with possibility to adding and removing recipes.
![Recipe book](https://raw.github.com/dkraczkowski/alchemy/master/docs/tut_ready.png)

What we need
-----------

 - apache with rewrite mod on
 - php >= 5.3.x
 - php pdo with sqlite on
 - basic php, html, css, js knowledge

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
   $app->run();```

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
```Fatal error: Uncaught exception 'alchemy\app\ApplicationResourceNotFoundException' with message 'No callable resource found' in...```

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



