<?php
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