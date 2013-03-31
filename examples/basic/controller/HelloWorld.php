<?php
/**
 * Alchemy Framework (http://alchemyframework.org/)
 *
 * @link      http://github.com/dkraczkowski/alchemy for the canonical source repository
 * @copyright Copyright (c) 2012-2013 Dawid Kraczkowski
 * @license   https://raw.github.com/dkraczkowski/alchemy/master/LICENSE New BSD License
 */
namespace basic\controller;
use alchemy\app\Controller;
/**
 * HelloWorld Controller
 */

class HelloWorld extends Controller
{
    public function sayHello()
    {
        echo _('Hello World!');
        echo _('Goodbye world!');
    }
}
