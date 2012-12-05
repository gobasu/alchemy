<?php
namespace app\controller;
use alchemy\app\Controller;
use alchemy\http\Response;
use alchemy\http\Headers;
use app\model\Product;
/**
 * SampleController
 *
 * @author: lunereaper
 */

class SampleController extends Controller
{
    public function index()
    {
        header('Content-Type:' . Headers::CONTENT_TYPE_TEXT);
        $model = Product::get('S10_1678');
        print_r($model);


    }
    public function bye($params = array())
    {
        if (isset($params['name'])) {
            echo "żegnaj świecei powiedział: " . $params['name'];
            return;
        }
        echo "żegnaj świecie";
    }


}
