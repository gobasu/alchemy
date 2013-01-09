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
namespace alchemy\http\router;
/**
 * Route class
 * Handles and parses application's route regex information
 */
class Route 
{
    public function __construct($pattern)
    {
        if ($pattern == self::WILD_CARD) {
            $this->pattern = '.*';
            return;
        }
        
        $this->parseUrlPattern($pattern);
    }
    
    public function __get($param)
    {
        if (!isset($this->params[$param])) {
            return null;
        }
        
        return $this->params[$param];
    }

    public static function setSeparator($separator = '/')
    {
        self::$separator = $separator;
    }
    
    public function getPattern()
    {
        return $this->pattern;
    }
    
    public function getParameters()
    {
        return $this->params;
    }
    
    /**
     * Checks whatever route match given uri
     * 
     * @param string $uri 
     * @return true if uri match route's pattern
     */
    public function isMatch($uri)
    {
        $match = preg_match('#' . $this->regex . '#', $uri, $matches);
        if (!$match) {
            return false;
        }
        $length = count ($matches);
        $paramKeys = array_keys($this->params);
        //fetch params
        for ($i = 1; $i < $length; $i++) {
            $value = $matches[$i];
            $this->params[$paramKeys[$i - 1]] = $value;
        }
        
        return true;
    }
    
    private function parseUrlPattern($pattern)
    {
        $separator = self::$separator;
        $pattern = rtrim($pattern, $separator);
        $this->pattern = $pattern;
        //sanitize / signs
        $pattern = str_replace($separator, '\\' . $separator, $pattern);
        $route = $this;
        $pattern = preg_replace_callback('#' . self::PATTERN_REGEX . '#', function($match) use ($route) {
            if (isset($match[2])) {
                $regex = '(' . substr($match[2],1) . ')';
            } else {
                $regex = '([^\/]+?)';
            }
            $route->_registerParam($match[1]);
            unset($route);
            return $regex;
        }, $pattern);
        
        $this->regex = '^' . $pattern . '\/?$';
    }

    /**
     * Registers param within the route
     *
     * @param $paramName
     */
    public function _registerParam($paramName)
    {
        $this->params[$paramName] = null;
    }
    
    
    /**
     * Url pattern
     * @var string
     */
    private $regex;
    
    private $pattern;
    
    /**
     * List of arguments found in url pattern
     * @var array
     */
    private $params = array();

    protected static $separator = '/';
    
    const WILD_CARD = '*';
    const PATTERN_REGEX = '\{\$([a-z0-9\-]+)[\\\]?(\:[^\/]+)?\}';
    
}