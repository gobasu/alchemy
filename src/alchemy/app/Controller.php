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

use alchemy\object\ILoadable;
use alchemy\event\EventDispatcher;
use alchemy\event\EventHub;
use alchemy\event\Event;

class ControllerException extends \Exception {}

/**
 * Application's controller class 
 */
abstract class Controller extends EventDispatcher implements ILoadable
{
    /**
     * Called when controller is loaded by ControllerClassName::load()
     */
    public function onLoad()
    {
    }

    /**
     * Called when controller was unloaded by Application::run
     */
    public function onUnload()
    {
    }

    /**
     * Dispatches an event to EventHub
     *
     * @param \alchemy\event\Event $e
     */
    public function dispatch(Event $e)
    {
        EventHub::dispatch($e);
        parent::dispatch($e);
    }
    
    /**
     * Loads controller object
     * 
     * @return Controller
     */
    public static function load()
    {
        $class = get_called_class();
        
        if (isset(self::$loaded[$class])) {
            return self::$loaded[$class];
        }
        
        self::$loaded[$class] = new $class();
        self::$loaded[$class]->onLoad();
        return self::$loaded[$class];
    }
    
    /**
     * Unload previously loaded controllers
     */
    public static function _unload()
    {
        foreach (self::$loaded as $c) {
            $c->onUnload();
        }
    }
    
    private static $loaded = array();
}