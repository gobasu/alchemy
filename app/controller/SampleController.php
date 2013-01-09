<?php
namespace app\controller;
use alchemy\app\Controller;
use alchemy\http\Response;

/**
 * Example controller class
 */

class SampleController extends Controller
{
    public function index()
    {
        $view = new \alchemy\ui\View();
        echo $view->render('default', array());
    }
    public function bye($params = array())
    {
        if (isset($params['name'])) {
            echo "Goodbye world, said: " . $params['name'];
            return;
        }
        echo "Goodbye cruel world";
    }


}
