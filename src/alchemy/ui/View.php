<?php
/**
 * Copyright (C) 2012 Dawid Kraczkowski
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to
 * deal in the Software without restriction, including without limitation the
 * rights to use, copy, modify, merge, publish, distribute, sublicense, and/or
 * sell copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED,
 * INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR
 * A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT
 * HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF
 * CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE
 * OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */
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
    public function __construct($dirname = 'view', $partial = 'shared')
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