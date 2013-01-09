<?php
use alchemy\event\EventDispatcher;
use alchemy\event\Event;
class EventDispatcherTestHandleEventWorkingException extends Exception {}
class EventDispatcherTest extends PHPUnit_Framework_TestCase
{
    public function testAddListener()
    {
        $ed = new EventDispatcher();
        $ed->addListener('OnEvent', array($this, 'handleOnEvent'));
        $this->assertTrue($ed->hasListener('OnEvent', array($this, 'handleOnEvent')));
    }
    
    public function testRemoveListener()
    {
        $ed = new EventDispatcher();
        $ed->addListener('OnEvent', array($this, 'handleOnEvent'));
        $this->assertTrue($ed->hasListener('OnEvent', array($this, 'handleOnEvent')));
        $ed->removeListener('OnEvent', array($this, 'handleOnEvent'));
        $this->assertFalse($ed->hasListener('OnEvent', array($this, 'handleOnEvent')));
    }
    
    /**
     * @expectedException EventDispatcherTestHandleEventWorkingException 
     */
    public function testDispatch()
    {
        $ed = new EventDispatcher();
        $ed->addListener('OnEvent', array($this, 'handleOnEvent'));
        $ed->dispatch(new OnEvent($this));
    }
    
    public function testBubbling()
    {
        $this->counter = 0;
        $ed = new EventDispatcher();
        $ed->addListener('OnEvent', array($this, 'handleEvent'));
        $ed->addListener('OnParentEvent', array($this, 'handleEvent2'));
        $ed->dispatch(new OnParentEvent($this));
        $this->assertEquals(3, $this->counter);
        
        $this->counter = 0;
        $ed = new EventDispatcher();    
        $ed->addListener('OnParentEvent', array($this, 'handleStopBubbling'));
        $ed->addListener('OnEvent', array($this, 'handleEvent2'));
        $ed->dispatch(new OnParentEvent($this));
        $this->assertEquals(0, $this->counter);
    }
    
    public function testPropagation()
    {
        $this->counter = 0;
        $ed = new EventDispatcher();
        $ed->addListener('OnEvent', array($this, 'handleEvent'));
        $ed->addListener('OnEvent', array($this, 'handleEvent2'));
        $ed->dispatch(new OnEvent($this));
        $this->assertEquals(3, $this->counter);

        $this->counter = 0;
        $ed = new EventDispatcher();
        $ed->addListener('OnEvent', array($this, 'handleStopPropagation'));
        $ed->addListener('OnEvent', array($this, 'handleEvent2'));
        $ed->addListener('OnEvent', array($this, 'handleEvent'));
        $ed->dispatch(new OnEvent($this));
        $this->assertEquals(0, $this->counter);
    }
    
    public function handleOnEvent(Event $e)
    {
        $this->counter++;
        throw new EventDispatcherTestHandleEventWorkingException('Dispatcher Exception');
    }
    
    public function handleEvent(Event $e)
    {
        $this->counter += 1;
    }
    
    public function handleEvent2(Event $e)
    {
        $this->counter += 2;
    }
    
    public function handleStopPropagation(Event $e)
    {
        $e->stopPropagation();
    }
    
    public function handleStopBubbling(Event $e)
    {
        $e->stopBubbling();
    }
    
    
    public $counter = 0;
    
}