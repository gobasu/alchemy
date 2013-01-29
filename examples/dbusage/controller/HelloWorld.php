<?php
namespace dbusage\controller;
use alchemy\app\Controller;
use dbusage\model\Customer;
/**
 * HelloWorld Controller
 */

class HelloWorld extends Controller
{
    public function sayHello()
    {
        header('Content-type: text/plain');
        $update = Customer::findAndModify(array('customerNumber>' => 450), array('+creditLimit' => 50), true);

        $data = Customer::findAll();

        print_r($update);
    }
}
