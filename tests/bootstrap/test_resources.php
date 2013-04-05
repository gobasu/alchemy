<?php
/**
 * Test resources and callbacks
 */

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

    public $var = 'test_var';
}