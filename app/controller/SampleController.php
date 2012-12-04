<?php
namespace app\controller;
use alchemy\app\Controller;
use alchemy\http\Response;
use alchemy\http\Headers;
use app\entity\Product;
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
        $entity = Product::get(1);
        Product::sort(array(Product::getSchema()->field => 1));

        Product::findAll(array(Product::getSchema()->field => 11));

        Product::sort(null);

        print_r($entity);
        print_r($entity->getSchema());
        print_r($entity);

        //foreach($entity->getSchema() as $propery) {
        //   print_r($propery);
        //}


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
