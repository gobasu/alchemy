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


        //update some fields
        $data = Customer::findAndModify(array('customerNumber>' => 450), array('+creditLimit' => 50), true);

        //get all fields from table
        $data = Customer::find();

        //get by pk
        $customer = Customer::get(103);

        //change propety and save
        $customer->phone = '111-222-333';
        $customer->save();

    }
}
