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
    /**
     * Constructor
     *
     * @param string $dirname relative path to your application folder pointing to views folder
     * @param string $partial relative path to you views folder pointing to partial views
     */
    public function __construct($dirname = 'view', $partial = 'partials')
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

        $partials = AL_APP_DIR . DIRECTORY_SEPARATOR . $dirname . DIRECTORY_SEPARATOR . $partial;
        if (is_dir($partials)) {
            $config['partials_loader'] = new \Mustache_Loader_FilesystemLoader($partials);
        }

        parent::__construct($config);
    }
}