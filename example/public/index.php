<?php
/**
 * YOUR BOOTSTRAP FILE
 *
 * If you would like to use other application namespace than example
 * just change your application's root directory name and you namespaces
 */
require_once realpath(dirname(__FILE__) . '/../../src/alchemy/app/Application.php');

use alchemy\app\Application;

$app = new Application(realpath(dirname(__FILE__) . '/../'));
$app->addRoute('*', 'example\controller\HelloWorld->sayHello'); //default route
$app->run();