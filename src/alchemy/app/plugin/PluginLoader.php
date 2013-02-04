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
namespace alchemy\app\plugin;
use alchemy\app\Plugin;
use alchemy\event\EventHub;
use alchemy\app\Loader;
class PluginLoaderException extends \Exception {}
class PluginLoader
{
    /**
     * Load all plugins from plugin directory
     *
     * @param string $pluginDir
     * @throws PluginLoaderException
     */
    public static function initialize($pluginDir = 'plugin')
    {
        $fullPluginDir = AL_APP_DIR . DIRECTORY_SEPARATOR . $pluginDir;
        if (!is_dir($fullPluginDir)) {
            if (!is_dir($pluginDir)) {
                throw new PluginLoaderException(sprintf('Plugin directory %s does not exists', $pluginDir));
            }
            $fullPluginDir = $pluginDir;
        }
        $dirIterator = new \DirectoryIterator($fullPluginDir);
        foreach ($dirIterator as $path)
        {
            if ($path->isDir()) continue;
            include_once $path->getPathname();
        }
    }

    /**
     * Register plugin and initialize listeners for given plugin
     * This is framework's internal method and shouldn't be called external
     *
     * @param \alchemy\app\Plugin $plugin
     * @internal
     */
    public static function _register($className)
    {
        self::$pluginList[$className] = new $className();
        self::$pluginList[$className]->onLoad();
        self::assignListeners(self::$pluginList[$className]);
    }

    /**
     * Assigns plugin's method to event specified in OnEvent annotation
     *
     * @param \alchemy\app\Plugin $plugin
     */
    private static function assignListeners(Plugin $plugin)
    {
        $className = get_class($plugin);

        $path = Loader::getPathForApplicationClass($className);

        //plugin's listener definition filename
        $listenerCacheFileName = AL_APP_CACHE_DIR . '/' . sha1($path);


        $listeners = array();
        //get definition
        if (is_readable($listenerCacheFileName) && filemtime($listenerCacheFileName) >= filemtime($path)) {
            $listeners = include_once $listenerCacheFileName;
        } else {
            $listeners = self::getListenersFromClassDefinition($className);
            self::saveCacheFile($listenerCacheFileName, $listeners);
        }

        foreach ($listeners as $event => $method) {
            EventHub::addListener($event, array($plugin, $method));
        }

    }

    private static function getListenersFromClassDefinition($className)
    {
        $listeners = array();
        $annotationReflection = new \alchemy\util\AnnotationReflection($className);
        foreach ($annotationReflection->getDeclaredMethods() as $method) {
            $annotation = $annotationReflection->getFromMethod($method);
            if (isset($annotation['OnEvent'])) {
                $listeners[$annotation['OnEvent']] = $method;
            }
        }
        return $listeners;
    }

    private static function saveCacheFile($file, $listeners)
    {
        $data = '<?php return array(';
        foreach ($listeners as $event => $method) {
            $data .= '\'' . $event . '\' => \'' . $method . '\',';
        }
        $data .= ');';

        file_put_contents($file, $data);
    }


    /**
     * @var array of Plugin
     */
    private static $pluginList = array();


}