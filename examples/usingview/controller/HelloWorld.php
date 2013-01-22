<?php
namespace configurable\controller;
use alchemy\app\Controller;
use alchemy\app\Application;
/**
 * HelloWorld Controller
 */

class HelloWorld extends Controller
{
    public function viewExample()
    {
        $view = new \usingview\view\PageView();
        return $view->render();
    }
}
