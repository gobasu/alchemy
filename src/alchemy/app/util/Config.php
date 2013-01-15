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
namespace alchemy\app\util;

/**
 * Manages multiply configs for one application
 */
class Config
{
    public function __construct($configDir = 'config')
    {
        $this->configDir = $configDir;

    }

    public function load()
    {
        $this->loaded = true;
        $this->loadCrossDomainConfig();
        $this->loadDomainConfig();
    }

    protected function loadCrossDomainConfig()
    {
        $crossDomainConfigPath = $this->configDir . '/' . Config::CROSSDOMAIN_CONFIG;
        $this->loadConfig($crossDomainConfigPath);
    }

    protected function loadDomainConfig()
    {
        if (!defined('AL_APP_HOST')) {
            define('AL_APP_HOST', isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : null);
        }
        $domainConfigPath = $this->configDir . '/' . AL_APP_HOST . '.php';
        $this->loadConfig($domainConfigPath);
    }

    private function loadConfig($file)
    {
        if (is_readable($file)) {
            $config = include_once $file;
            if (is_array($config)) {
                $this->config = array_merge($this->config , $config);
            }
        }
    }

    public function getConfig()
    {
        return $this->config;
    }

    public function isLoaded()
    {
        return $this->loaded;
    }

    public function get($name)
    {
        return isset($this->config[$name]) ? $this->config[$name] : null;
    }

    protected $loaded = false;
    protected $configDir;
    protected $config = array();
    const CROSSDOMAIN_CONFIG = '*.php';
}
