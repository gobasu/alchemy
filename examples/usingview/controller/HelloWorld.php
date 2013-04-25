<?php
/**
 * Alchemy Framework (http://alchemyframework.org/)
 *
 * @link      http://github.com/dkraczkowski/alchemy for the canonical source repository
 * @copyright Copyright (c) 2012-2013 Dawid Kraczkowski
 * @license   https://raw.github.com/dkraczkowski/alchemy/master/LICENSE New BSD License
 */
namespace usingview\controller;
use alchemy\app\Controller;
use alchemy\app\Application;
use alchemy\future\template\renderer\Mixture;
use usingview\view\PageView;

/**
 * HelloWorld Controller
 */

class HelloWorld extends Controller
{
    public function mixture()
    {
        $view = new PageView();
        echo $view;
    }

}
