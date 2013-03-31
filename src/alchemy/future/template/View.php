<?php
/**
 * Alchemy Framework (http://alchemyframework.org/)
 *
 * @link      http://github.com/dkraczkowski/alchemy for the canonical source repository
 * @copyright Copyright (c) 2012-2013 Dawid Kraczkowski
 * @license   https://raw.github.com/dkraczkowski/alchemy/master/LICENSE New BSD License
 */

namespace alchemy\future\template;

/**
 * Base view rendering class
 */
abstract class View extends \alchemy\event\EventDispatcher
{
    public function __set($name, $value)
    {
        $this->vars[$name] = $value;
    }

    public function __get($name)
    {
        return isset($this->vars[$name]) ? $this->vars[$name] : null;
    }

    /**
     * Used to dump template
     * @return string
     */
    public function __toString()
    {
        return (string) $this->render();
    }

    /**
     * Rendering logic goes here
     *
     * @return mixed
     */
    abstract public function render();

    /**
     * View vars
     * @var array
     */
    protected $vars = array();

}