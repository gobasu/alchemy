<?php
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