<?php
namespace app\controller;
use alchemy\app\ApplicationResourceNotFoundException;
use alchemy\future\app\Router;
use alchemy\future\app\router\Route;
use app\model\Recipe as RecipeModel;
use app\model\Recipe;
use app\model\Setup;
use app\view\Error as ErrorView;
use app\view\Index as IndexView;

class Page extends BaseController
{

    public function errorAction(\Exception $e)
    {

        //404 - display index
        if ($e instanceof ApplicationResourceNotFoundException) {
            return $this->indexAction();
        //display error page
        } else {
            $view = new ErrorView();
            $view->exception = $e;
            echo $view;
        }
    }

    /**
     * Displays page with all avaible recipies
     */
    public function indexAction()
    {
        $view = new IndexView();
        echo $view;
    }


    public function addrecipeAction()
    {
        if (empty($_POST)) {
            $this->getIndex();
        }
        $recipe = new RecipeModel();
        $recipe->set($_POST['recipe_data']);
        $recipe->created_on = time();
        $recipe->save();
        $recipe->saveIngredients(explode(',',$_POST['ingredients']));

        $this->getIndex();

    }

    public function deleterecipeAction($data)
    {
        RecipeModel::findAndRemove(array('recipe_id' => $data['id']));
        $this->getIndex();
    }

    protected function getIndex()
    {
        header('Location: /');
        exit();
    }

}