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
        $model->buyPrice = 21.11;

        echo PHP_EOL;
        $model->save();
        print_r($model);


        $p = new Product();
        $p->productName = "nowy produkt";
        $p->save();


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
