<?php
use alchemy\storage\Model;
/**
 * TestCollection
 *
 * @Pk id
 * @Collection testCollection
 * @storage alchemy\storage\sql\SQLite
 */
class TestCollection extends Model
{
    public static function import()
    {
        $sql = file_get_contents(__DIR__ . '/test.sql');
        $sql = explode(";\n" ,$sql);
        foreach ($sql as $q) {
            self::query($q);
        }


    }

    /**
     * @Param(type=number)
     */
    protected $id;

    /**
     * @Param(type=number)
     */
    protected $propertyA;

    /**
     * @Param(type=string)
     */
    protected $propertyB;

    protected $propertyC;
}