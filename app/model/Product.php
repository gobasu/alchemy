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
    /**
     * @Param(type=number, required=true)
     * @Validator(type=number,min-value=10,max-value=100)
     *
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
    protected $buyPrice;
}