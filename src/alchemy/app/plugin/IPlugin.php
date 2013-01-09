<?php
namespace alchemy\app\plugin;

interface IPlugin
{
    public function register();
    public function onLoad();
    public function onUnload();
}