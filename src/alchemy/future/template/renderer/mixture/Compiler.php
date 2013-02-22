<?php
/**
 * Alchemy Framework (http://alchemyframework.org/)
 *
 * @link      http://github.com/dkraczkowski/alchemy for the canonical source repository
 * @copyright Copyright (c) 2012-2013 Dawid Kraczkowski
 * @license   https://raw.github.com/dkraczkowski/alchemy/master/LICENSE New BSD License
 */
namespace alchemy\future\template\renderer\mixture;

class Compiler
{
    public function __construct()
    {
        $this->setContext(self::MAIN_FUNCTION_NAME);
    }

    public function compile(Node $tree)
    {
        foreach ($tree->getChildren() as $node) {
            if ($node->getType() == Node::NODE_TEXT) {
                $this->appendText($node->getValue());
                continue;
            }

            $handler = $node->getHandler();
            $handler = new $handler($node);
            $handler->handle($this);

            if ($node->hasChildren()) {
                $this->compile($node);
            }

        }
    }

    public function appendText($text)
    {
        $this->source[$this->context] .= $text;
    }

    public function setText($text)
    {
        $this->source[$this->context] = $text;
    }

    public function setContext($name)
    {
        if ($this->context) {
            $this->lastContext[] = $this->context;
        }
        $this->context = $name;
        if (!isset($this->source[$name])) {
            $this->source[$name] = '';
        }
    }

    public function removeContext($name)
    {
        unset($this->source[$name]);
    }

    public function gotoLastContext()
    {
        $context = array_pop($this->lastContext);
        if (!$context) {
            $context = self::MAIN_FUNCTION_NAME;
        }
        $this->context = $context;
    }

    protected $context = self::MAIN_FUNCTION_NAME;
    protected $lastContext = array();
    public $source = array();

    /**
     * $source = array(
        'render' <---main function
     * 'name' <--- other functions
     * )
     */

    const MAIN_FUNCTION_NAME = 'render';
    const TEMPLATE_CLASS = 'class Tpl extends Template
    {

    }';
}
