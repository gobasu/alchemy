<?php
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
