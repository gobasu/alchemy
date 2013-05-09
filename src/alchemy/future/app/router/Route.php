<?php

namespace alchemy\future\app\router;


class Route
{
    /**
     * Creates new route
     * @param $pattern string
     * Pattern is a expression separated by Route::$separator eg
     * some/path
     *
     * Pattern can have defined variables in it. Variable should start from $ right after
     * separator and it ends when separator sign is met or with pattern's end
     * some/${path}
     *
     * Extensive patterns
     * post/${action}?/${id}? means the same as
     *      post
     *      post/${action}
     *      post/${action}/${id}
     *
     * Advanced patterns
     * post/${action:a-z0-9+}
     */
    public function __construct($pattern)
    {
        $this->pattern = $pattern;
    }

    public function isMatch($uri)
    {
        $this->parse();
    }

    protected function parse()
    {
        if ($this->pattern == self::WILD_CARD) {
            $this->matchers[] = '.*';
            return;
        }
        $uri = rtrim($this->uri, self::$separator);
        $parts = explode(self::$separator, $this->pattern);
        $regex = '';
        foreach ($parts as $part) {
            //if part ends with ? add additional matcher to this route
            if (substr($part, -1) == '?') {
                $this->matchers[] = $regex;
            }
            $params = array();
            $part = preg_replace_callback('#' . self::PARAM_REGEX . '#', function($match) use ($params) {
                if (isset($match[2])) {
                    $regex = '(' . substr($match[2],1) . ')';
                } else {
                    $regex = '([^\/]+?)';
                }
                $params[] = $match[1];
                return $regex;
            }, $part);
            $regex .= self::$separator . $part;
            array_merge($this->params, $params);
        }
        $this->matchers[] = $regex;
    }

    private $pattern;
    private $params = array();
    private $uri;

    private $matchers = array();

    protected static $separator = '/';

    const WILD_CARD = '*';
    const PARAM_REGEX = '\$\{([a-z0-9\-]+)(\:[^\/]+?)?\}';

}