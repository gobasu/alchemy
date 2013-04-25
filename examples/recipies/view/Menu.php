<?php
namespace app\view;


class Menu extends BaseView
{
    public function render()
    {
        echo $this->template->render('menu.html');
    }
}