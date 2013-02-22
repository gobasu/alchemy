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
        $compiler->appendText('<?=$this->get(\'$' . $parameters[0] . '\')?>');
    }

    /**
     * @var \alchemy\future\template\renderer\mixture\Node
     */
    protected $node;
}
