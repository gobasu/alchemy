<?php
namespace app\view;
use app\model\Recipe as RecipeModel;
use app\model\Ingredient as IngredientModel;

class AddRecipe extends BaseView
{
    public function render()
    {
        $ingredients = IngredientModel::find();
        $json = array();
        foreach ($ingredients as $i) {
            $json[] = array('text' => $i->title, 'id' => $i->getPK());
        }
        $this->ingredients = json_encode($json);
        echo $this->template->render('recipe-add.html', $this->vars);
    }
}