<?php
/**
 * Alchemy Framework (http://alchemyframework.org/)
 *
 * @link      http://github.com/dkraczkowski/alchemy for the canonical source repository
 * @copyright Copyright (c) 2012-2013 Dawid Kraczkowski
 * @license   https://raw.github.com/dkraczkowski/alchemy/master/LICENSE New BSD License
 */
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
