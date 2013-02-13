Basics
======

Framework has been sharded into packages, every package has its own role in framework;

- `app` FW's core classes which setup all application and controlls flow
- `event` event package wich uses Observer pattern to make framework elastic
- `file` file manipullation classes (images, xls, etc... goes here)
- `future` packages that will appear in future
- `http` classes connected with http protocol and request handling
- `object`
- `security` acl and validation class
- `storage` package which focuses on persisting data
- `template` templating classes
- `util` utility classes
- `vendor` vendor classes and external API helpers (paypal, payu, ups, facebook, g+, etc...)

You can find example applications in [examples](/dkraczkowski/alchemy/blob/master/example) dir.

Example application structure
-----------------------------

To make advantage of alchemy framework you need to create your application's skeleton, consider following
example:

- `alchemy` framework dir
- `application` your application dir
    - `public` dir with public access
        - `.htaccess` rewrite file (if you are using apache)
        - `index.php` you bootstrap file
    - `controller` controller's dir
    - `model` model's dir
    - `view` view's dir
    - `cache` cache dir

Of course you can use totally different order but you should follow some conventions:
- Dirnames must be lower case
- Every file containing class which should be loaded dynamically **must have** the same name as the class.
- The namespace of given class must be corresponding to the dirname.

Example hello world application
-------------------------------

```php
<?php
require_once PATH_TO . '/alchemy/app/Application.php';
use alchemy\app\Application;

$app = Application::instance();
$app->setApplicationDir($PATH_TO_APPLICATION_ROOT);

//add routes here...
$app->onURI('*', function(){
  echo 'Hello World!';
});

//run application
$app->run();
```

`$PATH_TO_APPLICATION_ROOT` must be valid dirname pointing to the application root directory (the one that holds whole app's
direcotry structure)

Point your server root to `public` folder and rewrite all requests to `index.php` (bootstrap file)

Now if you go to `http://localhost/` url you should see:

    Hello World
