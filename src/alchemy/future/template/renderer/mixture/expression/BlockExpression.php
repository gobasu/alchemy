<?php
/**
 * Alchemy Framework (http://alchemyframework.org/)
 *
 * @link      http://github.com/dkraczkowski/alchemy for the canonical source repository
 * @copyright Copyright (c) 2012-2013 Dawid Kraczkowski
 * @license   https://raw.github.com/dkraczkowski/alchemy/master/LICENSE New BSD License
 */
namespace alchemy\future\template\renderer\mixture\expression;

use alchemy\future\template\renderer\mixture\ExpressionException;
use alchemy\future\template\renderer\mixture\IExpression;
use alchemy\future\template\renderer\mixture\Node;
use alchemy\future\template\renderer\mixture\Compiler;

class BlockExpression implements IExpression
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
        return 'block';
    }

    public static function getCloseTag()
    {
        return 'endblock';
    }

    public function handle(Compiler $compiler)
    {
        if ($this->node->getTagname() == self::getCloseTag()) {
            $compiler->gotoLastContext();
            return;
        }
        $parameters = $this->node->getParameters();

        if (!isset($parameters[1])) {
            throw new ExpressionException('Missing block name');
        }

        $func = 'userBlock' . self::sanitizeName($parameters[1]);
        $compiler->appendText('<?php $this->' . $func . '(); ?>');
        $compiler->setContext($func);
    }

    public static function sanitizeName($name)
    {
        return str_replace(array('-','.','/','+','*','&','^','#','@'), '_', $name);
    }

    /**
     * @var \alchemy\future\template\renderer\mixture\Node
     */
    protected $node;


}
