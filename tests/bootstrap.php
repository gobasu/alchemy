<?php
/**
 * Alchemy Framework (http://alchemyframework.org/)
 *
 * @link      http://github.com/dkraczkowski/alchemy for the canonical source repository
 * @copyright Copyright (c) 2012-2013 Dawid Kraczkowski
 * @license   https://raw.github.com/dkraczkowski/alchemy/master/LICENSE New BSD License
 */
set_include_path(get_include_path() . PATH_SEPARATOR . dirname(realpath("../src/")) . PATH_SEPARATOR . __DIR__ . '/bootstrap');
require_once 'src/alchemy/app/Application.php';

//alchemy framework constans
define('AL_APP_DIR', __DIR__);
define('AL_APP_CACHE_DIR', sys_get_temp_dir());
define('ASSETS_DIR', __DIR__ . '/bootstrap');

require_once 'test_resources.php';
require_once 'dummy_events.php';
require_once 'DummyConnection.php';
require_once 'DummyModel.php';
require_once 'TestCollection.php';
ob_start();