<?php
/**
 * Copyright (C) 2012 Dawid Kraczkowski
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to
 * deal in the Software without restriction, including without limitation the
 * rights to use, copy, modify, merge, publish, distribute, sublicense, and/or
 * sell copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED,
 * INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR
 * A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT
 * HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF
 * CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE
 * OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */
namespace alchemy\app;

//define core dir
if (!defined('AL_CORE_DIR')) {
    define('AL_CORE_DIR', realpath(dirname(__FILE__) . '/../'));
}

//Register core loader
require_once AL_CORE_DIR . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'Loader.php';
Loader::setup();

use alchemy\http\Router;
use alchemy\event\EventHub;
use alchemy\app\event\OnError;
use alchemy\app\event\OnShutdown;
use alchemy\app\Controller;
use alchemy\http\Request;
use alchemy\http\Response;
use alchemy\app\Loader;
use alchemy\storage\Session;

class ApplicationException extends \Exception {}
class ApplicationInvalidDirnameException extends ApplicationException {}
class Application
{
    /**
     * Constructor
     * 
     * @param string $appDir dir where you application files lies
     */
    public function __construct($appDir)
    {
        \alchemy\event\EventHub::initialize();
        if (!is_dir($appDir)) {
            throw new ApplicationInvalidDirnameException('Application dir does not exists');
        }
        define('AL_APP_DIR', $appDir);

        $cacheDir =  AL_APP_DIR . '/cache';
        if (!is_writable($cacheDir)) {
            $cacheDir = sys_get_temp_dir();
        }

        define('AL_APP_CACHE_DIR', $cacheDir);

        Loader::register(function($className){
            $path = Loader::getPathForApplicationClass($className);
            if (is_readable($path)) {
                require_once $path;
            }
        });

        $this->router = new Router();
    }

    public function onError($callable)
    {
        $this->onErrorHandler = new Resource($callable);
    }

    public function onStartup($callable)
    {
        $this->onStartupHandler = new Resource($callable);
    }

    /**
     * Runs application, handles request from global scope and translate them to fire up
     * right controller and method within the controller.
     * Unloads all loaded controllers of the end of execution
     *
     */
    public function run($mode = self::MODE_DEVELOPMENT)
    {
        Session::start();
        $request = Request::getGlobal();

        $this->router->setRequestMethod($request->getMethod());
        $this->router->setURI($request->getURI());
        $match = $this->router->getRoute();
        $this->resource = $this->router->getResource();
        if ($this->onStartupHandler && $this->onStartupHandler->isCallable()) {
            $this->onStartupHandler->call();
        }

        if (!$match || !$this->resource->isCallable()) {
            $e = new ApplicationException('No callable resource to run');
            EventHub::dispatch(new OnError($e));
            if ($this->onErrorHandler && $this->onErrorHandler->isCallable()) { //is app error handler registered
                $this->onErrorHandler->call($e);
                EventHub::dispatch(new OnShutdown($this));
                return false;
            } else {
                throw $e;
            }
        }
        $this->route = $match;

        try {
            //add execute listener
            ob_start();
            $this->executeResource();
            Controller::_unload();

        } catch (\Exception $e) {
            EventHub::dispatch(new OnError($e));
            if ($this->onErrorHandler && $this->onErrorHandler->isCallable()) { //is app error handler registered
                $this->onErrorHandler->call($e);
            } else {
                throw $e;
            }
        }
        EventHub::dispatch(new OnShutdown($this));
    }

    /**
     * Executes resource found in run method
     * DO NOT CALL IT EXTERNALLY
     */
    protected function executeResource()
    {
        $resource = $this->resource;
        $className = $resource->getClassName();
        $functionName = $resource->getFunctionName();

        if ($resource->isObject()) {
            if (is_subclass_of($className, 'alchemy\app\Controller')) {
                $object = call_user_func(array($className,'load'));
            } else {
                $object = new $className;
            }
            $response = call_user_func(array($object, $functionName), $this->route->getParameters());
        } else {
            $response = call_user_func(array($resource, 'call'), $this->route->getParameters());
        }

        $contents = trim(ob_get_contents());
        ob_end_clean();

        //contents were echoed
        if ($contents) {
            $response = new Response($contents);
        } elseif (is_string($response)) {
            $response = new Response($response);
        } elseif ($response instanceof Response) {
            //do nothing
        } elseif($response == null) {
            $response = new Response('');
        } else {
            $responseType = get_class($response);
            throw new ApplicationException('Not a valid response type of ' . $responseType);
        }

        echo $response;
    }



    /**
     * Adds route to handler resource
     * @see alchemy\http\Router::addResource
     *
     * @param $route    uri pattern to given resource
     *                  for example GET /posts/{$id}
     * @param $handler
     */
    public function addRoute($route, $handler)
    {
        $this->router->addResource($route, $handler);
    }

    /**
     * @var \alchemy\app\Resource
     */
    protected $onErrorHandler;

    /**
     * @var \alchemy\app\Resource
     */
    protected $onStartupHandler;

    /**
     * @var \alchemy\http\Router
     */
    protected $router;

    /**
     * @var \alchemy\app\Resource
     */
    protected $resource;

    protected $mode = self::MODE_DEVELOPMENT;

    protected static $instance;

    /**
     * @var \alchemy\http\router\Route
     */
    protected $route;

    const MODE_DEVELOPMENT = 1;
    const MODE_PRODUCTION = 2;

    const VERSION = '0.9.3';
}