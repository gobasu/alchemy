<?php
use alchemy\storage\db\Model;
use alchemy\storage\db\Property;

class ModelTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        \alchemy\storage\db\SchemaBuilder::disableCache();
    }

    public function testSchema()
    {
        $schema = DummyModel::getSchema();
        $this->assertInstanceOf('\alchemy\storage\db\ISchema', $schema);
        $this->assertEquals('DummyModel', $schema->getModelClass());
        $this->assertEquals('dummyCollection', $schema->getCollectionName());
        $this->assertInstanceOf('\alchemy\storage\db\Property', $prop = $schema->getPKProperty());
        $this->assertEquals('id', $prop->getName());
        $this->assertEquals(Property::TYPE_NUMBER, $prop->getType());
        unset($prop);

        $this->assertEquals('default', $schema->getConnectionName());
        $this->assertTrue($schema->propertyExists('propertyA'));
        $this->assertTrue($schema->propertyExists('propertyB'));
        $this->assertFalse($schema->propertyExists('propertyC'));
    }

    public function testSetGet()
    {
        $m = new DummyModel(1);
        $m->propertyA = 12;
        $this->assertEquals(12, $m->propertyA);
        $this->assertEquals(1, $m->getPK());

        $m = new DummyModel(array(
            'propertyA' => 1,
            'propertyB' => 2
        ));

        $this->assertEquals(1, $m->propertyA);
        $this->assertEquals(2, $m->propertyB);
        $this->assertNull($m->getPK());
        $this->assertTrue($m->isNew());
    }

    public function testIsChanged()
    {
        $m = new DummyModel(array(
            'propertyA' => 1,
            'propertyB' => 2
        ));

        $this->assertTrue($m->isChanged());
        $m->save();
        $this->assertFalse($m->isChanged());
        $m->propertyA = 11;
        $this->assertTrue($m->isChanged());
    }

    public function testGetChanges()
    {
        $data = array(
            'propertyA' => 1,
            'propertyB' => 2
        );
        $m = new DummyModel($data);
        $this->assertEquals($data, $m->getChanges());
    }

    public function testSet()
    {
        $data = array(
            'propertyA' => 1,
            'propertyB' => 2
        );
        $m = new DummyModel(1);
        $m->set($data);

        $this->assertEquals($data, $m->getChanges());
        $this->assertEquals(1, $m->getPK());
    }

    public function testSerialize()
    {
        $data = array(
            'propertyA' => 1,
            'propertyB' => 2
        );
        $m = new DummyModel(1);
        $m->set($data);
        $serialized = $m->serialize();
        $data[$m->getSchema()->getPKProperty()->getName()] = 1;
        $this->assertEquals($data, $serialized);
    }

    public function testCreate()
    {
        $data = array(
            'propertyA' => 1,
            'propertyB' => 2,
            'id'        => 1
        );
        $m = DummyModel::create($data);
        print_r($m);
    }
}