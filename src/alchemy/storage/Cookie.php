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
namespace alchemy\storage;

class CookieException extends \Exception {}
/**
 * Cookie handler class
 */
class Cookie
{
    /**
     * Alias for Cookie::set
     * @see Cookie::set
     */
    public function __set($name, $value)
    {
        self::set($name, $value);
    }

    /**
     * Sets cookie
     * @param $name cookie's name
     * @param $value cookie's value
     * @param int $expiration cookie's expiration name
     * @throws CookieException
     */
    public static function set($name, $value, $expiration = null)
    {
        if ($expiration) {
            $expiration = time() + $expiration;
        }
        $_COOKIE[$name] = $value;
        if (!is_array($value)) {
            setcookie($name, $value, $expiration, '/');
            return;
        }

        foreach ($value as $key => $v) {
            if (is_array($v)) {
                throw new CookieException('Invalid cookie value!');
            }
            setcookie(sprintf('%s[%s]', $name, $key), $v, $expiration, '/');
        }
    }

    /**
     * Alias for Cookie::get
     * @see Cookie::get
     */
    public function &__get($name)
    {
        $p = self::get($name);
        return $p;
    }

    /**
     * Gets cookie
     * @param $name cookie name
     * @return mixed
     */
    public static function &get($name)
    {
        $var = isset($_COOKIE[$name]) ? $_COOKIE[$name] : null;
        return $var;
    }
}