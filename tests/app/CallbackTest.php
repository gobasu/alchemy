<?php
class CallbackTest extends PHPUnit_Framework_TestCase
{
    public function testBindableResource()
    {
        
        //test object call
        $r = new alchemy\app\Callback('${class}->${method}');
        $r->bindParameters(array(
            'class'   => 'class',
            'method'  => 'method'
        ));
        $this->assertEquals($r->getClassName(), 'class');
        $this->assertEquals($r->getFunctionName(), 'method');
        
        //test function
        $r = new alchemy\app\Callback('${function}');
        $r->bindParameters(array(
            'function'  => 'function'
        ));
        $this->assertEquals($r->getFunctionName(), 'function');
        
        //test static call
        $r = new alchemy\app\Callback(array('${class}', '${method}'));
        $r->bindParameters(array(
            'class'   => 'class',
            'method'  => 'method'
        ));
        $this->assertEquals($r->getClassName(), 'class');
        $this->assertEquals($r->getFunctionName(), 'method');
        
    }
    
    public function testDefiningResources()
    {
        //test object call
        $r = new alchemy\app\Callback('${class}->${method}');
        $this->assertTrue($r->isObject());
        $this->assertFalse($r->isClosure());
        $this->assertFalse($r->isFunction());
        $this->assertEquals($r->getClassName(), '${class}');
        $this->assertEquals($r->getFunctionName(), '${method}');
        
        //test closure
        $r = new alchemy\app\Callback(function(){return true;});
        $this->assertFalse($r->isObject());
        $this->assertTrue($r->isClosure());
        $this->assertFalse($r->isFunction());
        
        //test static call
        $r = new alchemy\app\Callback(array('${class}', '${method}'));
        $this->assertFalse($r->isObject());
        $this->assertFalse($r->isClosure());
        $this->assertTrue($r->isFunction());
        $this->assertEquals($r->getClassName(), '${class}');
        $this->assertEquals($r->getFunctionName(), '${method}');
        
        //test function call
        $r = new alchemy\app\Callback('${function}');
        $r->bindParameters(array());
        $this->assertFalse($r->isObject());
        $this->assertFalse($r->isClosure());
        $this->assertTrue($r->isFunction());
        $this->assertEquals($r->getClassName(), null);
        $this->assertEquals($r->getFunctionName(), '${function}');
        
        
        $r = new alchemy\app\Callback('b');
        $r->bindParameters(array());
        $this->assertFalse($r->isObject());
        $this->assertFalse($r->isClosure());
        $this->assertTrue($r->isFunction());
        $this->assertEquals($r->getClassName(), null);
        $this->assertEquals($r->getFunctionName(), 'b');
    }
    
    public function testIsCallableResource()
    {
        $r = new alchemy\app\Callback('${class}->${method}');
        $this->assertFalse($r->isCallable());
        $r->bindParameters(array(
            'class'   => 'TestResource',
            'method'  => 'b'
        ));
        $this->assertTrue($r->isCallable($force = true));
        $this->assertEquals(2, call_user_func($r->getResource()));
        $resource = $r->getResource();

        $r = new alchemy\app\Callback(function(){return 2;});
        $this->assertTrue($r->isCallable());
        
        $r = new alchemy\app\Callback('a');
        $this->assertTrue($r->isCallable());
    }
}