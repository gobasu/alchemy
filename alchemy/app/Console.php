<?php
namespace alchemy\app;
use alchemy\util\CLI;

class Console extends Application
{
    public function __construct($appDir)
    {
        if (self::$instance) {
            throw new \Exception('Only one instance of Console can be created');
        }
        \alchemy\http\router\Route::setSeparator(':');
        parent::__construct($appDir);
        $this->router->setForceMode();
        self::$instance = $this;
    }

    public function run($mode = null)
    {
        $cli = fopen('php://stdin' , 'r');
        if ($this->onStartupHandler && $this->onStartupHandler->isCallable()) {
            $this->onStartupHandler->call();
        }
        while (true) {
            $this->input = $input = trim(fgets($cli, 1024));
            try {
                if (!$this->context) {
                    $this->router->setURI($input);
                    $route = $this->router->getRoute(true);
                    $resource = $this->router->getResource(true);
                    $resource->call($route->getParameters());
                } else {
                    call_user_func($this->context, $input);
                }
            } catch (\Exception $e) {
                if ($this->onErrorHandler && $this->onErrorHandler->isCallable()) { //is app error handler registered
                    $this->onErrorHandler->call($e);
                } else {
                    throw $e;
                }
            }

        }
    }

    /**
     * @return Console
     */
    public static function instance()
    {
        return self::$instance;
    }

    public function switchContext($callable)
    {
        $this->context = $callable;
    }

    public function removeContext()
    {
        $this->context = null;
    }

    public function getInput()
    {
        return $this->input;
    }

    /**
     *
     * @var Console
     */
    public static $instance;

    protected $input;

    /**
     * Context when cli commands will go
     * @var
     */
    protected $context;

}
