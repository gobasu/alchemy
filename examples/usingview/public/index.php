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
xhprof_enable(XHPROF_FLAGS_CPU + XHPROF_FLAGS_MEMORY); //<- xhprof profiler
//set plugin dir so framework will enable plugins functionality
$app->setPluginDir('plugins');
$app->setApplicationDir(realpath(dirname(__FILE__) . '/../'));
$app->onURI('*', 'usingview\controller\HelloWorld->mixture'); //default route
$app->run();
$xhprofData = xhprof_disable();
include_once "xhprof_lib/utils/xhprof_lib.php";
include_once "xhprof_lib/utils/xhprof_runs.php";
$xhprof = new XHProfRuns_Default();
$xhprof->save_run($xhprofData, md5($_SERVER['SCRIPT_NAME']));