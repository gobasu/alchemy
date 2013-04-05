<?php
/**
 * Alchemy Framework (http://alchemyframework.org/)
 *
 * @link      http://github.com/dkraczkowski/alchemy for the canonical source repository
 * @copyright Copyright (c) 2012-2013 Dawid Kraczkowski
 * @license   https://raw.github.com/dkraczkowski/alchemy/master/LICENSE New BSD License
 */
namespace usingview\view;
use alchemy\template\Mixture;

class PageView extends \alchemy\app\View
{
    public function render()
    {
        $context = array(
            'some'  => array(
                'var'   => array(
                    'in'    => array(1,2,3,4,5)
                )
            ),
            'title' => 'Sample title'
        );
        $renderer = new Mixture();
        $renderer->render('child.html', $context);
    }
}
