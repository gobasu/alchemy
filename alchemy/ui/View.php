<?php
namespace alchemy\ui;
require_once AL_CORE_DIR . '/vendor/Mustache/Autoloader.php';
\Mustache_Autoloader::register();
/**
 * Bridge between Mustache templates and alchemy FW
 * Default tags set to <% %> instead {{ }}
 */
class View extends \Mustache_Engine
{
    public function __construct($dirname = 'view')
    {
        $config = array(
            'template_class_prefix' => '__AlchemyTplClass',
            'cache'                 => AL_APP_CACHE_DIR,
            'loader'                => new \Mustache_Loader_FilesystemLoader(AL_APP_DIR . '/' . $dirname),
            'escape'                => function($value) {
                return htmlspecialchars($value, ENT_COMPAT, 'UTF-8');
            },
            'charset'               => 'UTF-8'
        );

        if (is_dir(AL_APP_DIR . '/' . $dirname . '/common')) {
            $config['partials_loader'] = new \Mustache_Loader_FilesystemLoader(AL_APP_DIR . '/' . $dirname . '/common');
        }

        parent::__construct($config);
    }
}