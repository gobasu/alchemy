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
namespace alchemy\storage\session;
/**
 * Session's namespace
 * Provides functionality to expire variables in it
 */
class SessionNamespace implements \ArrayAccess, \Countable
{
    public function offsetExists($offset)
    {
        return isset($data[$offset]);
    }

    public function count()
    {
        return count($this->data);
    }

    public function &offsetGet($offset)
    {
        if ($this->isExpired()) {
            $this->data = array();
            $this->setExpiration($this->expirationTime);
        }

        return $this->data[$offset];
    }

    public function __set($name, $value)
    {
        return $this->offsetSet($name, $value);
    }

    public function __get($name)
    {
        return $this->offsetGet($name);
    }

    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->data[] = $value;
            return;
        }
        $this->data[$offset] = $value;

    }

    public function offsetUnset($offset)
    {
        unset($this->data[$offset]);
    }

    /**
     * Sets session namespace's expiration time
     *
     * @param int $expire seconds to expire namespace
     */
    public function setExpiration($expire = 0)
    {
        $this->expirationTime = $expire;
        $this->expireAt = time() + $this->expirationTime;
    }

    /**
     * Checks if session expired
     * @return bool
     */
    public function isExpired()
    {
        return $this->expireAt && time() >= $this->expireAt;
    }

    public function __sleep()
    {
        return array('data', 'expirationTime', 'expireAt');
    }

    public function __wakeup()
    {
        if ($this->isExpired()) $this->data = array();
        if ($this->expirationTime) $this->expireAt = time() + $this->expirationTime;
    }

    protected $data = array();
    protected $expireAt = 0;
    protected $expirationTime = 0;
}