<?php
//require alchemy application
require_once realpath(dirname(__FILE__) . '/../../../src/alchemy/app/Application.php');
use alchemy\app\Application;


//initialize application and set application DIR
//this two lines are crucial
$app = Application::instance();
$app->setApplicationDir(realpath(dirname(__FILE__) . '/../'));

//run application
$app->run();