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
namespace alchemy\security\acl;

class Role
{

    public function __construct()
    {
        $this->restrictions['*'] = array();
        $this->restrictions['*']['*'] = false;
    }
    /**
     * Allows access to controller resource eg
     * user.edit allow to exec method edit on controller user
     * @param string $resource controller resource
     * @return Role
     */
    public function allow($resource)
    {
        if ($resource == '*') {
            $resource = array('*','*');
        } else {
            $resource = explode('.', $resource);
        }

        $controller = $resource[0];
        $action = isset($resource[1]) ? $resource[1] : '*';
        if (!isset($this->restrictions[$controller])) {
            $this->restrictions[$controller] = array();
            $this->restrictions[$controller]['*'] = false;
        }
        $this->restrictions[$controller][$action] = true;
        $this->restrictions[$controller]['?'] = true;

        return $this;
    }

    /**
     * @param $resource
     * @return Role
     */
    public function deny($resource)
    {
        if ($resource == '*') {
            $resource = array('*','*');
        } else {
            $resource = explode('.', $resource);
        }

        $controller = $resource[0];
        $action = isset($resource[1]) ? $resource[1] : '*';
        if (!isset($this->restrictions[$controller])) {
            $this->restrictions[$controller] = array();
            $this->restrictions[$controller]['*'] = false;
            $this->restrictions[$controller]['?'] = false;
        }
        $this->restrictions[$controller][$action] = false;

        return $this;
    }

    /**
     * @param $resource
     * @return mixed
     */
    public function hasAccess($resource)
    {
        $resource = explode('.', $resource);
        $resource[1] = isset($resource[1]) ? $resource[1] : '*';

        if (!isset($this->restrictions[$resource[0]])) {
            return $this->restrictions['*']['*'];
        }

        if (!isset($this->restrictions[$resource[0]][$resource[1]])) {
            return $this->restrictions[$resource[0]]['*'];
        }
        return $this->restrictions[$resource[0]][$resource[1]];
    }

    /**
     * Returns role restriction meta data
     * @return array restriction data
     */
    public function getRestrictionMeta()
    {
        return $this->restrictions;
    }

    private $restrictions = array();
}