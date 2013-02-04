<?php
namespace pluginexample\plugins;

class SamplePlugin extends \alchemy\app\Plugin
{
    /**
     * This function will be fired up when pluginexample\event\OnIndex event will be
     * dispatched from your application
     *
     * @see pluginexample\controller\Sample
     * @param \alchemy\event\Event $event
     * @OnEvent(pluginexample\event\OnIndex)
     */
    public function handleOnIndex(\alchemy\event\Event $event)
    {
        echo "Hello I am onIndex event responsive plugin example";

        //view calee
        //print_r($event->getCallee());
    }
}
//you have to register plugin by calling its own register static method
SamplePlugin::register();
