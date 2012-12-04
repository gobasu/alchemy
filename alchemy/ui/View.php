<?php
namespace alchemy\ui;
require_once AL_CORE_DIR . '/vendor/Mustache/Autoloader.php';
\Mustache_Autoloader::register();
/**
 * Bridge between Mustache templates and alchemy FW
 * Default tags set to {% %} instead {{ }}
 */
class View extends \Mustache_Engine
{
    public function __construct()
    {
        $config = array(
            'template_class_prefix' => '__AlchemyTplClass',
            'cache'                 => AL_APP_CACHE_DIR,
            'loader'                => new \Mustache_Loader_FilesystemLoader(AL_APP_DIR . '/view'),
            'escape'                => function($value) {
                return htmlspecialchars($value, ENT_COMPAT, 'UTF-8');
            },
            'charset'               => 'UTF-8'
        );

        if (is_dir(AL_APP_DIR . '/view/common')) {
            $config['partials_loader'] = new \Mustache_Loader_FilesystemLoader(AL_APP_DIR . '/view/common');
        }

        parent::__construct($config);
    }
}