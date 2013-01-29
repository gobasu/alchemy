<?php
namespace dbusage\model;
/**
 * @Collection customers
 * @Pk customerNumber
 */
class Customer extends \alchemy\storage\db\Model
{
    /**
     * @Param(type=number)
     */
    protected $customerNumber;
    /**
     * @Param(type=string)
     */
    protected $customerName;
    /**
     * @Param(type=string)
     */
    protected $contactLastName;
    /**
     * @Param(type=string)
     */
    protected $contactFirstName;
    /**
     * @Param(type=string)
     */
    protected $phone;
    /**
     * @Param(type=string)
     */
    protected $addressLine1;
    /**
     * @Param(type=string)
     */
    protected $addressLine2;
    /**
     * @Param(type=string)
     */
    protected $city;
    /**
     * @Param(type=string)
     */
    protected $state;
    /**
     * @Param(type=string)
     */
    protected $postalCode;
    /**
     * @Param(type=string)
     */
    protected $country;
    /**
     * @Param(type=number)
     */
    protected $salesRepEmployeeNumber;
    /**
     * @Param(type=number)
     */
    protected $creditLimit;
}
