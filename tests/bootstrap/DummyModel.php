<?php
use alchemy\storage\db\Model;
/**
 * DummyModel
 *
 * @Pk id
 * @Collection dummyCollection
 */
class DummyModel extends Model
{
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