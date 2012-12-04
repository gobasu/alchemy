<?php
namespace alchemy\http\router;

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
    
    public function getPattern()
    {
        return $this->pattern;
    }
    
    public function getParameters()
    {
        return $this->params;
    }
    
    /**
     * Checks whetever route match given uri
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
        $pattern = rtrim($pattern, '/');
        $this->pattern = $pattern;
        //sanitize / signs
        $pattern = str_replace('/', '\/', $pattern);
        $route = $this;
        $pattern = preg_replace_callback('#' . self::PATTERN_REGEX . '#', function($match) use ($route) {
            $route->_registerParam($match[0], $match[1]);
            return '([^\/]+?)';
        }, $pattern);
        
        $this->regex = '^' . $pattern . '\/?$';
    }
    
    public function _registerParam($paramVariable, $paramName)
    {
        $this->params[$paramName] = null;
    }
    
    
    /**
     * Url pattern coresponding to given resource
     * @var string
     */
    private $regex;
    
    private $pattern;
    
    /**
     * List of arguments found in url pattern
     * @var array
     */
    private $params = array();
    
    const WILD_CARD = '*';
    const PATTERN_REGEX = '\{\$([a-z0-9\-]+)\}';
    
}