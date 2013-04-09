<?php
namespace app\view;
use app\model\Recipe as RecipeModel;

class RecipeList extends BaseView
{
    public function render()
    {
        $this->recipes = RecipeModel::find();
        echo $this->template->render('recipe-list.html', $this->vars);
    }
}