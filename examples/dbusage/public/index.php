<?php
/**
 * YOUR BOOTSTRAP FILE
 *
 * If you would like to use other application namespace than example
 * just change your application's root directory name and you namespaces
 */
require_once realpath(dirname(__FILE__) . '/../../../src/alchemy/app/Application.php');
use alchemy\storage\DB;
use alchemy\app\Application;
use alchemy\storage\db\connection\MySQL;

DB::add(new Mysql('localhost', 'root', 'root', 'classicmodels'));

$app = Application::instance();
$app->setApplicationDir(realpath(dirname(__FILE__) . '/../'));
$app->addRoute('*', 'dbusage\controller\HelloWorld->sayHello'); //default route
$app->run();