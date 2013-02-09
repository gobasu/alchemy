<?php
/**
 * Alchemy Framework (http://alchemyframework.org/)
 *
 * @link      http://github.com/dkraczkowski/alchemy for the canonical source repository
 * @copyright Copyright (c) 2012-2013 Dawid Kraczkowski
 * @license   https://raw.github.com/dkraczkowski/alchemy/master/LICENSE New BSD License
 */
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
