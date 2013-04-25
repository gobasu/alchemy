<?php
namespace app\controller;
use app\model\Ingredient as IngredientModel;

class Ingredient extends BaseController
{
    //get all ingredients
    public function allAction()
    {
        $list = IngredientModel::find();
        $json = array();
        foreach ($list as $item) {
            $json[] = $item->serialize();
        }

        echo json_encode($json);
    }
}