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
namespace alchemy\app;

final class LoaderException extends \Exception {}
final class Loader
{
    /**
     * Setups framework autoloader
     */
    public static function setup()
    {
        self::register(function($className){
            $path = Loader::getPathForFrameworkClass($className);
            if (is_readable($path)) {
                require_once $path;
            }
        });
    }

    /**
     * Gets path for an user defined appllication's class
     *
     * @param string $className
     * @return string path to a class
     */
    public static function getPathForApplicationClass($className)
    {
        $className = substr($className, strpos($className, '\\'));

        $path = str_replace('\\', DIRECTORY_SEPARATOR, $className);
        return AL_APP_DIR . $path . '.php';
    }

    /**
     * Gets path for a framework core class
     *
     * @param string $className
     * @return string path to a class
     */
    public static function getPathForFrameworkClass($className)
    {
        //ommit first namespace element and replace \ with /
        $className = substr($className, strpos($className, '\\'));

        $path = str_replace('\\', DIRECTORY_SEPARATOR, $className);
        $path = AL_CORE_DIR . $path . '.php';
        return $path;
    }

    /**
     * Registers user defined loaders also used by Loader::setup
     *
     * @param $callable
     * @throws LoaderException when passed object is not callable
     */
    public static function register($callable)
    {
        if (!is_callable($callable)) {
            throw new LoaderException("Cannot register uncallable function as a loader");
        }
        spl_autoload_register($callable);
    }
}