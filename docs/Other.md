Other
=====

Custom error handling
---------------------

If you need to build 404 page or custom page when error occurs in your application you can use `Application->onError`
method in you bootstrap file and pass a callable parameter.

```php
<?php
require_once $VALID_PATH_TO . '/alchemy/app/Application.php';
use alchemy\app\Application;

$app = Application::instance();
$app->setApplicationDir($PATH_TO_APPLICATION_ROOT);
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
$app->onURL('*', function(){
  echo 'Hello World!';
});

//run application
$app->run();
```
