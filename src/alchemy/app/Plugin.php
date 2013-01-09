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
use alchemy\event\EventHub;
abstract class Plugin extends EventDispatcher implements plugin\IPlugin
{
    public function onLoad() {}
    public function onUnload() {}
    public function register()
    {
        $methods = get_class_methods(get_called_class());
        $prefixLength = strlen (self::EVENT_HANDLER_PREFIX);
        foreach ($methods as $method) {
            if (substr($method, 0, $prefixLength) != self::EVENT_HANDLER_PREFIX) {
                continue;
            }
            EventHub::addListener(substr($method, $prefixLength), array($this, $method));
        }
    }
    
    const EVENT_HANDLER_PREFIX = 'handle';
    
}