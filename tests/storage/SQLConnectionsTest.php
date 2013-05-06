<?php
use alchemy\storage\db\Model;
use alchemy\storage\DB;
use alchemy\storage\db\connection\SQLite;

//add sql connection
DB::add(new SQLite(SQLite::USE_MEMORY), 'sqlite');

class SQLConnectionTests extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        \alchemy\storage\db\SchemaBuilder::disableCache();
    }

    public function testNew()
    {
        $m = new TestCollection(array('propertyA' => 1, 'propertyB' => 2));
        $this->assertEquals($m->propertyA, 1);
        $this->assertEquals($m->propertyB, 2);
        $pk = 1;
        $m = new TestCollection($pk);
        $this->assertEquals($m->getPK(), $pk);
        $pk = 'stringPK';
        $m = new TestCollection($pk);
        $this->assertEquals($m->getPK(), $pk);

        TestCollection::import();
    }

    /**
     * @depends testNew
     * @expectedException \alchemy\storage\db\ModelException
     */
    public function testUnexistendPropertyException()
    {
        $m = new TestCollection(array('propertyA' => 1, 'propertyB' => 2, 'propertyC' => 3));

    }

    /**
     * @depends testNew
     */
    public function testFind()
    {
        $data = TestCollection::find();
        $this->assertEquals(12, count($data));

        $data = TestCollection::find(array('propertyA' => 1));
        $this->assertEquals(2, count($data));

        $data = TestCollection::find(array('propertyA' => array(1,2)));
        $this->assertEquals(4, count($data));

        $data = TestCollection::find(array('propertyA' => array(1,2,3)));
        $this->assertEquals(6, count($data));

        $data = TestCollection::find(array('propertyA>' => 1));
        $this->assertEquals(10, count($data));

        $data = TestCollection::find(array('propertyA<' => 5));
        $this->assertEquals(8, count($data));
    }

    /**
     * @depends testFind
     * @dataProvider collectionDataProvider
     */
    public function testGet($id, $propertyA, $propertyB)
    {

        $m = TestCollection::get($id);
        $this->assertEquals($m->propertyA, $propertyA);
        $this->assertEquals($m->propertyB, $propertyB);
        $this->assertEquals($m->getPK(), $id);

    }

    /**
     * @depends testFind
     */
    public function testfindAndModify()
    {
        $data = TestCollection::findAndModify(
            array('propertyA<' => 2), //find propertyA < 2
            array('propertyA' => 3),//change it to 3
            $returnData = true
        );
        $this->assertEquals(2, count($data));
        foreach ($data as $m) {
            $this->assertTrue($m->propertyA < 2);
        }

        $result = TestCollection::findAndModify(
            array('propertyU<' => 2), //find propertyA < 2
            array('propertyA' => 3)
        );

        $this->assertFalse($result);

        //import db again
        TestCollection::import();
    }

    /**
     * @depends testFind
     */
    public function testFindAndRemove()
    {
        $data = TestCollection::findAndRemove(
            array('propertyA<' => 2),
            $returnData = true
        );
        $this->assertEquals(2, count($data));
        foreach ($data as $m) {
            $this->assertTrue($m->propertyA < 2);
        }
        $data = TestCollection::find();
        $this->assertEquals(10, count($data));

        //import db again
        TestCollection::import();

    }

    /**
     * @depends testFind
     */
    public function testSave()
    {
        $m = TestCollection::get(1);
        $m->propertyA = 200;

        $m->save();
        $m = TestCollection::get(1);
        $this->assertEquals(200, $m->propertyA);
    }

    /**
     * @depends testSave
     */
    public function testDelete()
    {
        $m = TestCollection::get(1);
        $m->delete();
        $m = TestCollection::get(1);
        $this->assertFalse($m);
    }

    public function collectionDataProvider()
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