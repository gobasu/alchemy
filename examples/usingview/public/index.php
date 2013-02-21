<?php
/**
 * Alchemy Framework (http://alchemyframework.org/)
 *
 * @link      http://github.com/dkraczkowski/alchemy for the canonical source repository
 * @copyright Copyright (c) 2012-2013 Dawid Kraczkowski
 * @license   https://raw.github.com/dkraczkowski/alchemy/master/LICENSE New BSD License
 */
/**
 * YOUR BOOTSTRAP FILE
 *
 * If you would like to use other application namespace than example
 * just change your application's root directory name and you namespaces
 */
require_once realpath(dirname(__FILE__) . '/../../../src/alchemy/app/Application.php');
use alchemy\app\Application;
header('Content-Type: text/plain');
$app = Application::instance();
//set plugin dir so framework will enable plugins functionality
$app->setPluginDir('plugins');
$app->setApplicationDir(realpath(dirname(__FILE__) . '/../'));
$app->onURI('*', 'usingview\controller\HelloWorld->index'); //default route
$app->run();