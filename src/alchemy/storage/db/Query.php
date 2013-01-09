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
namespace alchemy\storage\db;
/**
 * Query
 *
 * @author: lunereaper
 */

class Query
{
    public static function all()
    {

    }

    public static function one()
    {

    }

    public static function eq($param, $value)
    {
        return array('#eq' => array($param, $value));
    }

    public static function neq($param, $value)
    {

    }

    public static function gt($param, $value)
    {

    }

    public static function lt($param, $value)
    {

    }

    public static function between($param, $min, $max)
    {

    }

    public static function gte($param, $value)
    {

    }

    public static function lte($param, $value)
    {

    }

    public static function regex($param, $value)
    {

    }
}
Query::all(Query::gt('field',12));