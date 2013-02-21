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

class I18nExpression implements IExpression
{
    public static function isBlock()
    {
        return false;
    }

    public static function getOpenTag()
    {
        return 'i18n';
    }

    public static function getCloseTag()
    {
    }

    public function handleOpen(Node $node)
    {

    }

    public function handleClose(Node $node)
    {

    }


}
