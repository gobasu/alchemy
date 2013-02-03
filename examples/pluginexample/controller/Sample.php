<?php
namespace pluginexample\controller;
use pluginexample\event;

class Sample extends \alchemy\app\Controller
{
    public function index()
    {
        //dispatch OnIndex event here!
        $this->dispatch(new event\OnIndex($this));
    }
}
