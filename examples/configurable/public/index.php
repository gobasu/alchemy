<?php
/**
 * CONFIG usage example.
 *
 * To use different configs on different host just create the dir where you will put config(s)
 * files, and use `Application->setConfigDir` method.
 *
 * Config files should return an array with the contents, example below:
 * <code>
 * <?php
 * return array(
 *     'MY_CONFIG_VALUE'    => true,
 *     'OTHER_CONFIG_VALUE' => false
 * );
 * </code>
 *
 * Config file must have the name responding to host where it should be loaded. There is
 * also cross-host config file name '*.php' which will be loaded on all hosts
 *
 *
 */
require_once realpath(dirname(__FILE__) . '/../../../src/alchemy/app/Application.php');

use alchemy\app\Application;

$app = Application::instance();
$app->setApplicationDir(realpath(dirname(__FILE__) . '/../'));
$app->setConfigDir('config');
$app->addRoute('*', 'example\controller\HelloWorld->sayHello'); //default route
$app->run();