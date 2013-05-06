<?php
use alchemy\storage\Model;
/**
 * DummyModel
 *
 * @Pk id
 * @Collection dummyCollection
 * @storage DummyConnection
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