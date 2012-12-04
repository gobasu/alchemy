<?php
namespace alchemy\storage\db;

interface ISchema
{
    public function getPKProperty();
    public function getPropertyNameList();
    public function getProperty($name);
    public function getCollection();

}
