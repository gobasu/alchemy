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

        $model->buyPrice = mt_rand(1,100);

        $model->save();



        $set = Product::findOne(array('productLine' => 'Motorcycles'), array('buyPrice' => 1));
        print_r($set);

        $set = Product::findAll(array('productLine' => 'Motorcycles'));
        print_r($set);



        $p = new Product();
        $p->productCode = '1a1a1a1';
        $p->productLine = 'Motorcycles';
        $p->productName = "nowy produkt";
        $p->save();

        $set = Product::getMotorcycles();
        print_r($set);

        $p->delete();


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
