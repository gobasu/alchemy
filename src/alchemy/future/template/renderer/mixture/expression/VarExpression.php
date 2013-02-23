<?php
/**
 * Alchemy Framework (http://alchemyframework.org/)
 *
 * @link      http://github.com/dkraczkowski/alchemy for the canonical source repository
 * @copyright Copyright (c) 2012-2013 Dawid Kraczkowski
 * @license   https://raw.github.com/dkraczkowski/alchemy/master/LICENSE New BSD License
 */
namespace alchemy\future\template\renderer\mixture\expression;

use alchemy\future\template\renderer\mixture\IExpression;
use alchemy\future\template\renderer\mixture\Node;
use alchemy\future\template\renderer\mixture\Compiler;

class VarExpression implements IExpression
{
    public function __construct(Node $node)
    {
        $this->node = $node;
    }

    public static function isBlock()
    {
        return false;
    }

    public static function getOpenTag()
    {
    }

    public static function getCloseTag()
    {
    }

    public function handle(Compiler $compiler)
    {
        $parameters = $this->node->getParameters();
        $compiler->appendText('<?=' . self::getVariableReference($parameters[0]) . '?>');
    }

    public static function getVariableReference($name)
    {
        //current variable
        if ($name == '.' || $name == 'this' || $name == '$.') {
            return '$this->stack->get(\'.\')';
        }

        //loop variables
        if ($name{0} == '@') {
            $name = substr($name, 1);

            //undefined varname return null
            if (!isset(self::$loopVars[$name])) {
                return 'null';
            }
            return EachExpression::getVariable($name);
        }

        //normal variables from different expressions
        if ($name{0} == '$') {
            return '$this->stack->get(\''. substr($name, 1) . '\')';
        }

        //normal variables from var expression
        return '$this->stack->get(\''. $name . '\')';
    }

    /**
     * @var \alchemy\future\template\renderer\mixture\Node
     */
    protected $node;

    protected static $loopVars = array('odd' => true, 'even' => true, 'index' => true, 'value' => true, 'key' => true);
}
