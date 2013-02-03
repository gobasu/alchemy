<?php
namespace pluginexample\controller;
use pluginexample\event;

class Sample extends \alchemy\app\Controller
{
    public function index()
    {
        $this->dispatch(new event\OnIndex($this));
    }
}
