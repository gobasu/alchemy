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

class EachExpression implements IExpression
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
        return 'each';
    }

    public static function getCloseTag()
    {
        return 'endeach';
    }

    public function handle(Compiler $compiler)
    {
        if ($this->node->getTagname() == self::getCloseTag()) {
            $name = array_pop(self::$iteratedItem);
            $compiler->appendText('<?php endforeach;?>');

            return;
        }

        self::$iteratedItem++;

        $parameters = $this->node->getParameters();
        if ($parameters[2] != 'in') {
            throw new CompilerException('Used unknown expression ' . $parameters[2] . ' in ' . $parameters[0] . ' tag');
        }

        if ($parameters[1]{0} == '$') {
            $parameters[1] = substr($parameters[1], 1);
        }
        self::$iteratedItem[] = $parameters[1];

        $index = self::getVariable('index');
        $key = self::getVariable('key');
        $value = self::getVariable('value');
        $odd = self::getVariable('odd');
        $even = self::getVariable('even');

        //looping through a variable
        if ($parameters[3]{0} == '$') {
            $var = VarExpression::getVariableReference($parameters[3]);
            $compiler->appendText('<?php ' . $index . ' = 0; foreach((array)' . $var . ' as ' . $key . ' => ' . $value . '):');
            $compiler->appendText('$this->stack->set(\'' . $parameters[1] . '\', ' . $value . ');');
            $compiler->appendText(
                $index . '++;' .
                $odd . ' = ' . $index . '%2 ? false : true;' .
                $even . ' = !' . $odd . '; ' .
            '?>');
            return;
        }

        if (!preg_match('#^(\d+)\.\.(\d+)$#', $parameters[3], $matches)) {
            throw new CompilerException('Used unknown expression ' . $parameters[3] . ' in ' . $parameters[0] . ' tag');
        }
        $range = self::getVariable('range');


        $compiler->appendText('<?php ' . $index .' = 0; ' . $range .' = range(' . $matches[1] . ',' . $matches[2] . '); foreach(' . $range . ' as ' . $key . ' => ' . $value . '):');
        $compiler->appendText('$this->stack->set(\'' . $parameters[1] . '\', ' . $value . ');');
        $compiler->appendText(
            $index . '++;' .
            $odd . ' = ' . $index . '%2 ? false : true;' .
            $even . ' = !' . $odd . '; ' .
        '?>');

    }

    public static function getVariable($type, $iteratedItem = null)
    {
        return '$_each' . $type . '_' . ($iteratedItem ? $iteratedItem : end(self::$iteratedItem));
    }


    /**
     * @var \alchemy\future\template\renderer\mixture\Node
     */
    protected $node;

    protected static $rangeNumber = 0;

    /**
     * Disalows to mix scopes
     * @var int
     */
    protected static $iteratedItem = array();

}
