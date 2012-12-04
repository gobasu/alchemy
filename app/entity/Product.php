<?php
namespace app\entity;
use alchemy\storage\db\Entity;
/**
 * Product
 *
 * @Connection default
 *
 * @Pk customerNumber
 */

class Product extends Entity
{
    public static function getByName($name)
    {

    }

    /**
     * @Param(type=number, required=true, name="customer_number")
     * @Validator(type=number,min-value=10,max-value=100)
     *
     */
    protected $customerNumber;

    /**
     * @Param(type=number, required=false)
     */
    protected $checkNumber;

    /**
     * @Param(type=date, name="payment_date")
     */
    protected $paymentDate;

    /**
     * @Param(type=bool)
     */
    protected $amount;
}