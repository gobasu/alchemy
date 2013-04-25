<?php
namespace app\view;

class Index extends BaseView
{
    public function render()
    {
        return $this->template->render('index.html', $this->vars);
    }
}