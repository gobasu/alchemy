<?php
use alchemy\storage\db\Model;
use alchemy\storage\DB;
/**
 * TestCollection
 *
 * @Pk id
 * @Collection testCollection
 * @connection sqlite
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