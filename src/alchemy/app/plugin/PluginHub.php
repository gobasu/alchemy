<?php
namespace alchemy\app\plugin;

class PluginLoaderException extends \Exception {}
class PluginLoader
{
    public static function initialize($pluginDir = 'plugin')
    {
        $fullPluginDir = AL_APP_DIR . DIRECTORY_SEPARATOR . $pluginDir;
        if (!is_dir($fullPluginDir)) {
            if (!is_dir($pluginDir)) {
                throw new PluginLoaderException(sprintf('Plugin directory %s does not exists', $pluginDir));
            }
            $fullPluginDir = $pluginDir;
        }
        $dirIterator = new \RecursiveDirectoryIterator($fullPluginDir);
        foreach ($dirIterator as $path)
        {
            if ($path->isDir()) continue;
            echo $path;
        }
    }
    
    public static function unload()
    {
    }
    
    public static function registerPlugin(IPlugin $plugin)
    {
        
    }
    
    private static $plugins;
    
}