<?php
/**
 * BOOTSTRAP FILE
 * POINT ALL REQUEST TO THIS ONE
 */
require_once realpath(dirname(__FILE__) . '/../../alchemy/app/Application.php');
use alchemy\app\Application;

$app = new Application(realpath(dirname(__FILE__) . '/../'));

$app->addRoute('/bye', 'app\controller\SampleController->bye');
$app->addRoute('/bye/{$name}', 'app\controller\SampleController->bye');
$app->addRoute('/closure', function(){
    echo 'Hello world from closure';
});
$app->addRoute('*', 'app\controller\SampleController->index'); //default route
$app->run();