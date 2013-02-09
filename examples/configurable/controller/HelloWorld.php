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
    public function sayHello()
    {
        if(Application::instance()->get('IS_HELLO')) {
            echo 'hello world';
        }

        if(Application::instance()->get('IS_LOCALHOST')) {
            echo ' from localhost!';
        } else {
            echo '!';
        }

        if(Application::instance()->get('YET_ANOTHER_VALUE')) {
            echo 'I\'ve got the value:' . Application::instance()->get('YET_ANOTHER_VALUE');
        }
    }
}
