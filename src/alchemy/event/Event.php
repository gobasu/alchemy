<?php
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