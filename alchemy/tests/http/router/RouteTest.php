<?php

class RouteTest extends PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider routeDataProvider
     * @param string $pattern route pattern
     * @param string $uri uri
     * @param array $asserts list of route's param asserts
     */
    public function testPattern($pattern, $uri, $match, $asserts = array())
    {
        
        $r = new alchemy\http\router\Route($pattern, 'a');
        if ($match) {
            $this->assertTrue($r->isMatch($uri));
            
            foreach ($asserts as $p => $v) {
                $this->assertEquals($r->{$p}, $v);
            }
        } else {
            $this->assertFalse($r->isMatch($uri));
        }
        
    }
    
    public function routeDataProvider()
    {
        return array(
            array('a/{$p1}', 'a/v1', true, array('p1' => 'v1')),
            array('{$p1}', 'v1', true, array('p1' => 'v1')),
            array('a/{$p1}', 'v1', false),
            array('post/{$action}/{$id}', 'post/edit/2', true, array('action' => 'edit', 'id' => 2))
        );
    }
}