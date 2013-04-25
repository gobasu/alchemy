<?php
namespace app\controller;

use alchemy\app\Controller;
use app\model\Setup;

class BaseController extends Controller
{
    public function onLoad()
    {
        if (defined('RUN_SETUP')) {
            Setup::buildDatabase();
        }
    }
}