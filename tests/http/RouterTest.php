<?php
use alchemy\http\Router;
class RouterTest extends PHPUnit_Framework_TestCase
{
    public function testBaseRouterUsage()
    {
        $router = new Router();
        $router->setRequestMethod('POST');
        $router->setURI('post/edit/2');
        $router->addResource('GET post/all', function(){});
        $router->addResource('GET post/edit', 'a');
        $router->addResource('POST item/edit/{$id}', 'b');
        $router->addResource('POST post/edit/{$id}', 'b');
        $router->addResource('PUT post/edit/{$i}', 'd');

        $route = $router->getRoute();
        $resource = $router->getResource();
        $this->assertEquals($route->getPattern(), 'post/edit/{$id}');
        $this->assertEquals($route->id, '2');
        $this->assertEquals($resource->getFunctionName(), 'b');
        $this->assertTrue($resource->isFunction());
    }
    
    public function testWildCards()
    {
        $router = new Router();
        $router->setRequestMethod('POST');
        $router->setURI('post/edit/2');
        $router->addResource('GET post/all', function(){});
        $router->addResource('GET post/edit', 'a');
        $router->addResource('* item/edit/{$id}', 'b');
        $router->addResource('POST *', 'TestResource->a');
        
        $router->setURI('unknown/url');
        $resource = $router->getResource();
        $this->assertTrue($resource->isObject());
        $this->assertEquals('TestResource', $resource->getClassName());
        $this->assertEquals('a', $resource->getFunctionName());
    }

    public function testSetSeparator()
    {
        \alchemy\http\router\Route::setSeparator(':');
        
        $router = new Router();
        $router->setURI('sample:separator');
        $router->addResource('{$resource}:{$action}', '{$resource}->{$action}');

        $resource = $router->getResource();

        $this->assertEquals($resource->getClassName(), 'sample');
        $this->assertEquals($resource->getFunctionName(), 'separator');

        \alchemy\http\router\Route::setSeparator('/');
    }


}