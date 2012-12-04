<?php
class SamplePlugin implements alchemy\app\IPlugin
{
    public function register()
    {
        $this->addListener('SomeEvent', 'onSomeEvent');
    }
    public function onLoad()
    {
        
    }
    
    public function onUnload()
    {
        
    }
    
    public function handleApplicationError()
    {
        
    }
    
    /**
     * listens on event OnCheckoutEvent 
     */
    public function handleOnCheckout()
    {
        
    }
}