<?php
/**
 * Alchemy Framework (http://alchemyframework.org/)
 *
 * @link      http://github.com/dkraczkowski/alchemy for the canonical source repository
 * @copyright Copyright (c) 2012-2013 Dawid Kraczkowski
 * @license   https://raw.github.com/dkraczkowski/alchemy/master/LICENSE New BSD License
 */
namespace usingview\controller;
use alchemy\app\Controller;
use alchemy\app\Application;
use alchemy\future\template\renderer\Mixture;
/**
 * HelloWorld Controller
 */

class HelloWorld extends Controller
{
    public function mixture()
    {
        $context = array(
            'some'  => array(
                'var'   => array(
                    'in'    => array(1,2,3,4,5)
                )
            ),
            'title' => 'Przykładowy tytuł'
        );

        $mix = new Mixture(__DIR__ . '/../view/tpl/');
        $mix->setCacheDir(__DIR__ . '/../cache/');
        $mix->render('sample.html', $context);
    }

    public function smarty()
    {
        require_once __DIR__ . '/../libs/Smarty.class.php';

        $context = array(
            'some'  => array(
                'var'   => array(
                    'in'    => array(1,2,3,4,5)
                )
            ),
            'title' => 'Przykładowy tytuł'
        );

        $smarty = new \Smarty();
        $smarty->setTemplateDir( __DIR__ . '/../view/tpl/');

        $smarty->setCompileDir(__DIR__ . '/../cache/');
        $smarty->assign('some', $context['some']);
        $smarty->assign('title',  $context['title']);
        $smarty->display('smarty.html');
    }
}
