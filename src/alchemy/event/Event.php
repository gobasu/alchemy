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
 * Base Event Class 
 */
class Event
{
    /**
     * Constructor
     * 
     * @param object $callee passes  triggering class to an Event
     * 
     * @example
     * class SampleController extends \alchemy\app\Controller
     * {
     *      public function example()
     *      {
     *          $this->dispatch(new Event($this));
     *      }
     * }
     */
    public function __construct($callee = null)
    {
        $this->callee = $callee;
    }
    
    /**
     * Prevents event from propagation
     *
     */
    public function stopPropagation()
    {
        $this->propagates = false;
    }
    
    /**
     * Prevents event from bubbling
     *
     */
    public function stopBubbling()
    {
        $this->bubbles = false;
    }

    /**
     * Returns object which has dispatched the event
     * @return null|object
     */
    public function getCallee()
    {
        return $this->callee;
    }

    /**
     * Checks if event is propagating
     *
     * @return bool true if is propagating
     */
    public function _isPropagating()
    {
        return $this->propagates;
    }

    /**
     * Checks if event is bubbling
     *
     * @return bool true if is bubbling
     */
    public function _isBubbling()
    {
        return $this->bubbles;
    }

    public function __toString()
    {
        return sprintf('[Event] #%s', get_class($this));
    }

    
    private $propagates = true;
    private $bubbles = true;
    private $callee;
}