<?php
/**
 * Created by JetBrains PhpStorm.
 * User: lunereaper
 * Date: 09.04.2013
 * Time: 11:16
 * To change this template use File | Settings | File Templates.
 */

namespace app\view;


use alchemy\template\Mixture;

class Error extends BaseView
{
    public function render()
    {
        return $this->template->render('error.html', $this->vars);
    }
}