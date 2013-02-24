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
use alchemy\future\template\renderer\mixture\CompilerException;

class IfExpression implements IExpression
{
    public function __construct(Node $node)
    {
        $this->node = $node;
    }
    public static function isBlock()
    {
        return true;
    }

    public static function getOpenTag()
    {
        return 'if';
    }

    public static function getCloseTag()
    {
        return 'endif';
    }

    /**
     * Parses if expression
     * {% if $var %}
     *
     * {% if $var not *value* %}
     *
     * {% if $var is odd %} <-- loop proposal (will parse into if($_eachodd_x)
     *
     * {% if $var is 3|string|number
     * @param \alchemy\future\template\renderer\mixture\Compiler $compiler
     */
    public function handle(Compiler $compiler)
    {
        if ($this->node->getTagname() == self::getCloseTag()) {
            $compiler->appendText('<?endif;?>');
            return;
        }

        $parameters = $this->node->getParameters();

        $length = count($parameters);

        if ($length < 2) {
            throw new CompilerException('If expression requires at least one parameter, none given');
        }

        if ($length == 2) {
            $compiler->appendText('<?php if(' . VarExpression::getVariableReference($parameters[1]) . '):?>');
            return;
        }

        if ($parameters[2] == 'not') {
            $not = '!';
        } else {
            $not = null;
        }

        $iteratedItem = substr($parameters[1],1);

        if ($length == 4) {
            switch (true) {
                case $parameters[3] == 'number':
                    $compiler->appendText('<?php if(' . $not . 'is_numeric(' . VarExpression::getVariableReference($parameters[1]) . ')):?>');
                    break;
                case $parameters[3] == 'odd' && EachExpression::isIterationAvailable($iteratedItem):
                case $parameters[3] == 'even' && EachExpression::isIterationAvailable($iteratedItem):
                case $parameters[3] == 'first' && EachExpression::isIterationAvailable($iteratedItem):
                case $parameters[3] == 'last' && EachExpression::isIterationAvailable($iteratedItem):
                    $compiler->appendText('<?php if(' . $not . EachExpression::getVariable($parameters[3], $iteratedItem) . '):?>');
                    break;
                case is_numeric($parameters[3]):
                    $compiler->appendText('<?php if(' . VarExpression::getVariableReference($parameters[1]) . ' ' . ($not ? $not : '='   ) . '= ' . $parameters[3] . '):?>');
                    break;
                default:
                    $compiler->appendText('<?php if(' . VarExpression::getVariableReference($parameters[1]) . ' ' . ($not ? $not : '='   ) . '= \'' . addslashes($parameters[3]) . '\'):?>');
                    break;
            }
            return;
        }

        throw new CompilerException('An error occured in if expression');
    }

    /**
     * @var Node
     */
    protected $node;
}
