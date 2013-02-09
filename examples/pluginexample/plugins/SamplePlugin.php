<?php
/**
 * Alchemy Framework (http://alchemyframework.org/)
 *
 * @link      http://github.com/dkraczkowski/alchemy for the canonical source repository
 * @copyright Copyright (c) 2012-2013 Dawid Kraczkowski
 * @license   https://raw.github.com/dkraczkowski/alchemy/master/LICENSE New BSD License
 */
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
