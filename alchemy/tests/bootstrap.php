<?php
set_include_path(dirname(__FILE__) . '/../' . PATH_SEPARATOR . get_include_path());
require_once 'app/Application.php';

//test functions and classes
function a()
{
    return 1;
}
function b()
{
    return 2;
}
class TestResource
{
    public function a()
    {
        return a();
    }

    public static function b()
    {
        return b();
    }
    
    public function throwError($msg)
    {
        throw new Exception($msg);
    }
}
class OnEvent extends \alchemy\event\Event {}
class OnParentEvent extends OnEvent {}

