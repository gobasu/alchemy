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
namespace alchemy\event;

class EventHub
{
    /**
     * Initializes application event hub
     *
     * @return bool true if event hub was previously initialized
     */
    public static function initialize()
    {
        if (self::$isInitialized) {
            return self::$isInitialized;
        }
        self::$dispatcher = new EventDispatcher();
        self::$isInitialized = true;
    }

    /**
     * Dispatches an event through all hub's listeners
     *
     * @see EventDispatcher::dispatch
     */
    public static function dispatch(Event $event)
    {
        return self::$dispatcher->dispatch($event);
    }

    /**
     * Adds listener to the event hub
     *
     * @see EventDispatcher::addListener
     */
    public static function addListener($event, $listener)
    {
        return self::$dispatcher->addListener($event, $listener);
    }

    /**
     *
     * @see EventDispatcher::hasListener
     */
    public static function hasListener($event, $listener)
    {
        return self::$dispatcher->hasListener($listener);
    }

    /**
     *
     * @see EventDispatcher::removeListener
     */
    public static function removeListener($event, $listener)
    {
        return self::$dispatcher->removeListener($event, $listener);
    }
    
    /**
     * 
     * @var alchemy\event\EventDispatcher
     */
    private static $dispatcher;
    
    /**
     *
     * @var boolean
     */
    private static $isInitialized = false;
}