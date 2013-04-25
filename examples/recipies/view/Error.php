<?php
namespace app\view;


use alchemy\template\Mixture;

class Error extends BaseView
{
    public function render()
    {
        return $this->template->render('error.html', $this->vars);
    }
}