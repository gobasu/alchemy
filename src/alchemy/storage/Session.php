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
use alchemy\storage\session\SessionNamespace;
class Session
{
    /**
     * Starts the session
     */
    public static function start()
    {
        if (self::isActive()) {
            return false;
        }
        if (self::$handler) {
            session_set_save_handler(
                array(self::$handler, 'open'),
                array(self::$handler, 'close'),
                array(self::$handler, 'read'),
                array(self::$handler, 'write'),
                array(self::$handler, 'destroy'),
                array(self::$handler, 'gc')
            );
        }
        session_start();
        self::$data = &$_SESSION;
        self::$sessionId = session_id();
        return true;
    }

    /**
     * Checks if session is started
     *
     * @return bool
     */
    public static function isActive()
    {
        if (self::$sessionId) {
            return true;
        }
        return false;
    }

    /**
     * Gets session id
     * @return string
     */
    public static function getID()
    {
        return self::$sessionId;
    }

    /**
     * Sets session id
     * @param $id
     */
    public static function setID($id)
    {
        self::$sessionId = $id;
        session_id($id);
    }

    /**
     * Sets session handler
     *
     * @param session\IHander $handler
     */
    public static function setHandler(\alchemy\storage\session\IHander $handler)
    {
        self::$handler = $handler;
    }

    /**
     * Destroys the session
     */
    public static function destroy()
    {
        session_destroy();
    }

    /**
     *
     * @param $name
     * @return SessionNamespace
     */
    public static function &get($name)
    {
        if (!isset(self::$data[$name])) {
            self::$data[$name] = new SessionNamespace();
        }
        return self::$data[$name];
    }

    private static $data = array();

    /**
     * @var \alchemy\storage\session\IHander
     */
    private static $handler;

    /**
     * @var string
     */
    protected static $sessionId;
}