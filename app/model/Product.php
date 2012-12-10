<?php
namespace app\model;
use alchemy\storage\db\Model;
/**
 * Product
 *
 * @Connection default
 * @Collection products
 * @Pk productCode
 */

class Product extends Model
{
    public static function getMotorcycles()
    {
        $schema = self::getSchema();
        $fieldList = '`' . implode('`,`', $schema->getPropertyList()) . '`';
        $sql = 'SELECT ' . $fieldList . '
            FROM ' . $schema->getCollectionName() . '
            WHERE productLine = "Motorcycles"';

        return self::getConnection()->query($sql, $schema);
    }

    /**
     * @Param(type=number, required=true)
     */
    protected $productCode;

    /**
     * @Param(type=string, required=false)
     */
    protected $productName;

    /**
     * @Param(type=date)
     */
    protected $productLine;

    /**
     * @Param(type=number)
     */
    protected $buyPrice = 0.00;
}