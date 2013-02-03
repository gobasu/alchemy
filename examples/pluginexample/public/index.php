<?php
/**
 * YOUR BOOTSTRAP FILE
 *
 * If you would like to use other application namespace than example
 * just change your application's root directory name and you namespaces
 */
require_once realpath(dirname(__FILE__) . '/../../../src/alchemy/app/Application.php');
use alchemy\app\Application;

$app = Application::instance();
//set plugin dir so framework will enable plugins functionality
$app->setPluginDir('plugins');
$app->setApplicationDir(realpath(dirname(__FILE__) . '/../'));
$app->onURL('*', 'pluginexample\controller\Sample->index'); //default route
$app->run();