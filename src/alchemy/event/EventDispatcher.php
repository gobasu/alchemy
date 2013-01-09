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

/**
 * EventDispacher class
 * Observer
 */
class EventDispatcher
{
    /**
     * Add Event Listener
     *
     * @param string $event class name of event you are listening at
     * @param callable $listener callable function call eg. array('YourClass','method')
     * 
     * @example
     * $e = new EventDispatcher();
     * $e->addListener('OnError', function($evt) {
     *  print_r($evt);
     *  echo 'Event appeared';
     * });
     */
    public function addListener($event, $listener)
    {
        if (!isset($this->listeners[$event])) {
            $this->listeners[$event] = array();
        }
        $this->listeners[$event][] = new Listener($listener);
    }

    /**
     * Removes event listener
     *
     * @param string $event event class name
     * @param callable $listener 
     * @return boolean
     */
    public function removeListener($event, $listener)
    {
        if (!isset($this->listeners[$event])) {
            return;
        }
        foreach ($this->listeners[$event] as $k => &$l)
        {
            if (!$l->isA($listener)) {
                continue;
            }
            unset ($this->listeners[$event][$k]);
        }
    }

    /**
     * Check if given object has event listener
     *
     * @param string $event
     * @param function $listener
     * @return boolean
     */
    public function hasListener($event, $listener)
    {
        if (!isset($this->listeners[$event])) {
            return false;
        }
        foreach ($this->listeners[$event] as $k => &$l)
        {
            if (!$l->isA($listener)) {
                continue;
            }
            return true;
        }
        return false;
    }

    /**
     * Dispatch event supports bubbling and propaging
     *
     * @param Event $event
     * @return boolean false if no listeners were executed otherwise true
     */
    public function dispatch(Event $event)
    {
        $className = get_class($event);
        $list = class_parents($className);

        array_unshift($list, $className);
        $listenerExist = false;

        foreach ($list as $eventClass) {
            if (!$event->_isBubbling()) {
                break;
            }
            if (!isset($this->listeners[$eventClass])) {
                continue;
            }
            foreach ($this->listeners[$eventClass] as $listener) {
                $listener->call($event);
                $listenerExist = true;
                if (!$event->_isPropagating()) {
                    break 2;
                }
            }
        }
        return $listenerExist;
    }

    /**
     * @param array
     */
    protected $listeners = array();

}
