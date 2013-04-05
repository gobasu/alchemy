<?php
use alchemy\template\Mixture;
class MixtureTest extends PHPUnit_Framework_TestCase
{
    public function testVarPassing()
    {
        $var = array(
            'number'    => 1,
            'string'    => 'string',
            'array'     => array(2,3,4,5,6),
            'object'    => new TestResource()
        );
        $mixture = new Mixture(ASSETS_DIR);
        $mixture->disableCache();
        $result = $mixture->render('tpl_var_test.html', $var);
        $this->assertEquals('1, string, 2, 3, 1, test_var', $result);
    }

    public function testEach()
    {
        $var = array(
            'number'    => 1,
            'string'    => 'string',
            'array'     => array(2,3,4,5,6),
            'object'    => new TestResource()
        );
        $mixture = new Mixture(ASSETS_DIR);
        $mixture->disableCache();
        $result = $mixture->render('tpl_each_test.html', $var);

        $this->assertEquals('1:0:2::1::1|2:1:3:::1:|3:2:4::::1|4:3:5:::1:|5:4:6:1:::1|---
0|1|2|3|4|5|6|7|8|9|10|', $result);

    }

    public function testIf()
    {
        $var = array(
            'number'    => 1,
            'string'    => 'string',
            'array'     => array(2,3,4,5,6),
            'object'    => new TestResource()
        );
        $mixture = new Mixture(ASSETS_DIR);
        $mixture->disableCache();
        $result = $mixture->render('tpl_if_test.html', $var);
        $this->assertEquals('numbernnumber2nstring', $result);
    }

    public function testImport()
    {
        $var = array(
            'number'    => 1,
            'string'    => 'string',
            'array'     => array(2,3,4,5,6),
            'object'    => new TestResource()
        );
        $mixture = new Mixture(ASSETS_DIR);
        $mixture->disableCache();
        $result = $mixture->render('tpl_import_test.html', $var);
        $this->assertEquals('1:0:2::1::1|2:1:3:::1:|3:2:4::::1|4:3:5:::1:|5:4:6:1:::1|---
0|1|2|3|4|5|6|7|8|9|10|numbernnumber2nstringtest test
10, string, 2, 3, 1, test_var', $result);

    }

    public function testExtend()
    {
        $var = array(
            'number'    => 1,
            'string'    => 'string',
            'array'     => array(2,3,4,5,6),
            'object'    => new TestResource()
        );
        $mixture = new Mixture(ASSETS_DIR);
        $mixture->disableCache();
        $result = $mixture->render('tpl_extends_test.html', $var);
        $this->assertEquals('<!DOCTYPE html>
<html>
<head>
    <title>My Title</title>
</head>
<body>
</body>
</html>', $result);


    }

    public function testAddHelper()
    {
        $var = array(
            'number'    => 1,
            'string'    => 'string',
            'array'     => array(2,3,4,5,6),
            'object'    => new TestResource()
        );
        $mixture = new Mixture(ASSETS_DIR);
        $mixture->addHelper('helper-test', function(){
            $args = func_get_args();
            array_pop($args);
            print_r($args);
        });
        $mixture->disableCache();
        $result = $mixture->render('tpl_helper_test.html', $var);
        $this->assertEquals('Array
(
    [0] => 1
    [1] => string
    [2] => 12
)
', $result);
    }

}