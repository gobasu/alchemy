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

//handle other urls
$app->onURI('/{$controller}/{$action}/{$id}', 'app\controller\{$controller}->{$action}Action');//new,delete for recipe
$app->onURI('/{$controller}/{$action}', 'app\controller\{$controller}->{$action}Action');//new,delete for recipe
$app->onURI('/{$controller}', 'app\controller\{$controller}->indexAction');//new,delete for recipe
$app->onURI('*', 'app\controller\Page->indexAction');

//run application
$app->run();