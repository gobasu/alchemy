<?php
namespace alchemy\event;

/**
 * Listener class used by EventDispatcher
 */
class Listener
{
    /**
     * @param mixed $listener callable
     */
    public function __construct($listener)
    {
        $this->listener = $listener;
    }

    /**
     * Calls listener
     *
     * @param Event $event
     */
    public function call(Event $event)
    {
        if (!$this->listener || !is_callable($this->listener)) return;
        call_user_func($this->listener, $event);
    }

    /**
     * Checks whatever passed listener is the same as this one
     *
     * @param $listener
     * @return bool
     */
    public function isA($listener)
    {
        return $listener == $this->listener;
    }
    
    private $listener;
}