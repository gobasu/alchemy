<?php
/**
 * Alchemy Framework (http://alchemyframework.org/)
 *
 * @link      http://github.com/dkraczkowski/alchemy for the canonical source repository
 * @copyright Copyright (c) 2012-2013 Dawid Kraczkowski
 * @license   https://raw.github.com/dkraczkowski/alchemy/master/LICENSE New BSD License
 */
namespace alchemy\template\renderer;
require_once AL_CORE_DIR . '/vendor/Mustache/Autoloader.php';
\Mustache_Autoloader::register();
/**
 * Bridge between Mustache templates and alchemy FW
 * Default tags set to <% %> instead {{ }}
 */
class Mustache extends \Mustache_Engine
{
    /**
     * Constructor
     *
     * @param string $dirname relative path to your application folder pointing to views folder
     * @param string $partial relative path to you views folder pointing to partial views
     */
    public function __construct($dirname = 'view', $partial = 'shared')
    {
        $config = array(
            'template_class_prefix' => '__AlchemyTplClass',
            'cache'                 => AL_APP_CACHE_DIR,
            'loader'                => new \Mustache_Loader_FilesystemLoader(AL_APP_DIR . '/' . $dirname),
            'escape'                => function($value) {
                return htmlspecialchars($value, ENT_COMPAT, 'UTF-8');
            },
            'charset'               => 'UTF-8',
            'helpers'               => array(
                'i18n'  => function ($text) {
                    return 'asasas' . $text;
                }
            )
        );

        $partials = AL_APP_DIR . DIRECTORY_SEPARATOR . $dirname . DIRECTORY_SEPARATOR . $partial;
        if (is_dir($partials)) {
            $config['partials_loader'] = new \Mustache_Loader_FilesystemLoader($partials);
        }
        $this->setHelpers(\alchemy\app\Application::instance()->getConfig());
        parent::__construct($config);
    }
}