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
interface IHander
{
    /**
     * The open callback works like a constructor in classes and is
     * executed when the session is being opened. It is the first
     * callback function executed when the session is started automatically
     * or manually with session_start().
     * Should return true if success, false for failure
     *
     * @param $savePath
     * @param $sessionName
     * @return boolean
     */
    public function open($savePath, $sessionName);

    /**
     * The close callback works like a destructor in classes and is executed
     * after the session write callback has been called.
     * It is also invoked when session_write_close() is called.
     * Should return value should be true for success, false for failure.
     *
     * @return boolean
     */
    public function close();

    /**
     * The read callback must always return a session encoded (serialized) string,
     * or an empty string if there is no data to read.
     * This callback is called internally by PHP when the session starts or
     * when session_start() is called.
     * Before this callback is invoked PHP will invoke the open callback.
     *
     * @param $sessionId
     */
    public function read($sessionId);
    public function write($sessionId, $sessionData);
    public function destroy($sessionId);
    public function gc($maxLifetime);

}
