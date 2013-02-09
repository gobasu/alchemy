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
        $this->assertEquals('testCollection', $schema->getCollectionName());
        $this->assertInstanceOf('\alchemy\storage\db\Property', $prop = $schema->getPKProperty());
        $this->assertEquals('id', $prop->getName());
        $this->assertEquals(Property::TYPE_NUMBER, $prop->getType());
        unset($prop);

        $this->assertEquals('sqlite', $schema->getConnectionName());
        $this->assertTrue($schema->propertyExists('propertyA'));
        $this->assertTrue($schema->propertyExists('propertyB'));
        $this->assertFalse($schema->propertyExists('propertyC'));
    }

    /**
     * @depends testSchema
     */
    public function testNew()
    {
        $m = new DummyModel(array('propertyA' => 1, 'propertyB' => 2));
        $this->assertEquals($m->propertyA, 1);
        $this->assertEquals($m->propertyB, 2);
        $pk = 1;
        $m = new DummyModel($pk);
        $this->assertEquals($m->getPK(), $pk);
        $pk = 'stringPK';
        $m = new DummyModel($pk);
        $this->assertEquals($m->getPK(), $pk);

        DummyModel::import();
    }

    /**
     * @depends testNew
     * @expectedException \alchemy\storage\db\ModelException
     */
    public function testUnexistendPropertyException()
    {
        $m = new DummyModel(array('propertyA' => 1, 'propertyB' => 2, 'propertyC' => 3));

    }

    /**
     * @depends testNew
     */
    public function testFind()
    {
        $data = DummyModel::find();
        $this->assertEquals(12, count($data));

        $data = DummyModel::find(array('propertyA' => 1));
        $this->assertEquals(2, count($data));

        $data = DummyModel::find(array('propertyA' => array(1,2)));
        $this->assertEquals(4, count($data));

        $data = DummyModel::find(array('propertyA' => array(1,2,3)));
        $this->assertEquals(6, count($data));

        $data = DummyModel::find(array('propertyA>' => 1));
        $this->assertEquals(10, count($data));

        $data = DummyModel::find(array('propertyA<' => 5));
        $this->assertEquals(8, count($data));
    }

    /**
     * @depends testFind
     * @dataProvider dummyModelDataProvider
     */
    public function testGet($id, $propertyA, $propertyB)
    {

        $m = DummyModel::get($id);
        $this->assertEquals($m->propertyA, $propertyA);
        $this->assertEquals($m->propertyB, $propertyB);
        $this->assertEquals($m->getPK(), $id);

    }

    /**
     * @depends testFind
     */
    public function testfindAndModify()
    {
        $data = DummyModel::findAndModify(
            array('propertyA<' => 2), //find propertyA < 2
            array('propertyA' => 3),//change it to 3
            $returnData = true
        );
        $this->assertEquals(2, count($data));
        foreach ($data as $m) {
            $this->assertTrue($m->propertyA < 2);
        }

        $result = DummyModel::findAndModify(
            array('propertyU<' => 2), //find propertyA < 2
            array('propertyA' => 3)
        );

        $this->assertFalse($result);

        //import db again
        DummyModel::import();
    }

    /**
     * @depends testFind
     */
    public function testFindAndRemove()
    {
        $data = DummyModel::findAndRemove(
            array('propertyA<' => 2),
            $returnData = true
        );
        $this->assertEquals(2, count($data));
        foreach ($data as $m) {
            $this->assertTrue($m->propertyA < 2);
        }
        $data = DummyModel::find();
        $this->assertEquals(10, count($data));

        //import db again
        DummyModel::import();

    }

    /**
     * @depends testFind
     */
    public function testSave()
    {
        $m = DummyModel::get(1);
        $m->propertyA = 200;

        $m->save();
        $m = DummyModel::get(1);
        $this->assertEquals(200, $m->propertyA);
    }

    /**
     * @depends testSave
     */
    public function testDelete()
    {
        $m = DummyModel::get(1);
        $m->delete();
        $m = DummyModel::get(1);
        $this->assertFalse($m);
    }

    public function dummyModelDataProvider()
    {

        return array(
            array(1, 1, 'a'),
            array(2, 2, 'b'),
            array(3, 3, 'c'),
            array(4, 4, 'd'),
            array(5, 5, 'e'),
            array(6, 6, 'f'),
            array(7, 1, 'A'),
            array(8, 2, 'B'),
            array(9, 3, 'C'),
            array(10, 4, 'D'),
            array(11, 5, 'E'),
            array(12, 6, 'F')
        );
    }
}