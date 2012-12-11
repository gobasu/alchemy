<?php
//xhprof_enable(XHPROF_FLAGS_CPU + XHPROF_FLAGS_MEMORY); <- xhprof profiler

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

/*
$xhprofData = xhprof_disable();
include_once "xhprof_lib/utils/xhprof_lib.php";
include_once "xhprof_lib/utils/xhprof_runs.php";
$xhprof = new XHProfRuns_Default();
$xhprof->save_run($xhprofData, md5($_SERVER['SCRIPT_NAME']));
*/