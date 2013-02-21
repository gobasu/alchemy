<?php
namespace usingview\plugins;

class HTMLHeadPlugin extends \alchemy\app\Plugin
{
    /**
     * @param alchemy\event\Event $event
     * @OnEvent(view\OnHeadRender)
     */
    public function onHeadTagRender(\alchemy\event\Event $event)
    {
        $event->getCallee()->getBySectionName('head')->append('<base href="" />');
    }
}

HTMLHeadPlugin::register();
