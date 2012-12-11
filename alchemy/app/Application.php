<?php
namespace alchemy\app;

//define core dir
if (!defined('AL_CORE_DIR')) {
    define('AL_CORE_DIR', realpath(dirname(__FILE__) . '/../'));
}

//Register core loader
require_once AL_CORE_DIR . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'Loader.php';
Loader::setup();

use alchemy\http\Router;
use alchemy\event\EventDispatcher;
use alchemy\app\event\OnUndefinedResource;
use alchemy\app\event\OnBeforeResourceCall;
use alchemy\app\event\OnAfterResourceCall;
use alchemy\app\event\OnError;
use alchemy\app\Controller;
use alchemy\http\Request;
use alchemy\http\Response;
use alchemy\app\Loader;

class ApplicationException extends \Exception {}
class ApplicationInvalidDirnameException extends ApplicationException {}
class Application extends EventDispatcher
{
    /**
     * Constructor
     * 
     * @param string $appDir dir where you application files lies
     */
    public function __construct($appDir)
    {
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
            //ommit first namespace element and replace \ with /
            $path = Loader::getPathForApplicationClass($className);
            if (is_readable($path)) {
                require_once $path;
            }
        });

        $this->router = new Router();

    }

    /**
     * Runs application, handles request from global scope and translate them to fire up
     * right controller and method within the controller.
     * Unloads all loaded controllers of the end of execution
     *
     */
    public function run()
    {
        $request = Request::getGlobal();

        $this->router->setRequestMethod($request->getMethod());
        $this->router->setURI($request->getURI());
        $match = $this->router->getRoute();
        $this->resource = $this->router->getResource();

        if (!$match || !$this->resource->isCallable()) {
            $this->dispatch(new OnUndefinedResource($this));
            return;
        }
        $this->route = $match;
        //add execute listener
        ob_start();
        $this->addListener('alchemy\app\event\OnBeforeResourceCall', array($this, '_executeResource'));
        $this->dispatch(new OnBeforeResourceCall($this));

        Controller::_unload();
    }

    /**
     * Executes resource found in run method
     * DO NOT CALL IT EXTERNALLY
     */
    public function _executeResource()
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
        $this->dispatch(new OnAfterResourceCall($this));
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
            $this->dispatch(new OnError($this));
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
     * @var \alchemy\http\Router
     */
    private $router;

    /**
     * @var \alchemy\app\Resource
     */
    private $resource;

    /**
     * @var \alchemy\http\router\Route
     */
    private $route;

    const VERSION = '0.9.2';
}