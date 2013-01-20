<?php
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
