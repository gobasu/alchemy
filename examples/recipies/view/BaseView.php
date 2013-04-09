<?php
namespace app\view;

use alchemy\app\View;
use alchemy\template\Mixture;

abstract class BaseView extends View
{
    public function __construct()
    {
        //helper for dynamically nested views
        //load and display view
        Mixture::addHelper('view', function($viewClass){

            try {
                if (class_exists($viewClass)) {
                    $viewObject = new $viewClass;
                    echo $viewObject;
                } else {
                    throw new \Exception();
                }
            } catch (\Exception $e) {
                echo '<div class="alert">
                    <strong>Warning!</strong> Trying to load non existing view class: ' . $viewClass . '
                </div>';
            }
        });

        //helper for displaying php styled variable dump
        Mixture::addHelper('pre', function($var){
            echo '<pre>';
            print_r($var);
            echo '</pre>';
        });

        //create template object, set template and cache dir for it
        $this->template = new Mixture(realpath(__DIR__ . '/../template'));
        $this->template->setCacheDir(realpath(__DIR__ . '/../template/cache'));
    }

    /**
     * @var Mixture
     */
    protected $template;
}