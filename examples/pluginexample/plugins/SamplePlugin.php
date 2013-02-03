<?php
namespace pluginexample\plugins;

class SamplePlugin extends \alchemy\app\Plugin
{
    public function onLoad()
    {
        $this->addListener('pluginexample\event\OnIndex', array($this, 'handleOnIndex'));
    }

    public function handleOnIndex(\alchemy\event\Event $event)
    {
        echo "Hello I am on index plugin example";

        //view calee
        print_r($event->getCallee());
    }
}
SamplePlugin::register();
