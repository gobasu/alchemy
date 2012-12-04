<?php
use alchemy\util\AnnotationReflection;

class AnnotationReflectionTest extends PHPUnit_Framework_TestCase
{
    public function testGetFromClass()
    {
        $annotation = new AnnotationReflection('SampleAnnotatedClass');
        $classAnnotation = $annotation->getFromClass();

        $this->assertArrayHasKey('author', $classAnnotation);
        $this->assertArrayHasKey('param1', $classAnnotation);
        $this->assertArrayHasKey('param2', $classAnnotation);
        $this->assertArrayHasKey('param3', $classAnnotation);

        $this->assertEquals($classAnnotation['author'], 'sample@author.com');

        $this->assertTrue(count($classAnnotation['param1']) == 4);
        $this->assertEquals($classAnnotation['param1'][0], 111);
        $this->assertEquals($classAnnotation['param1'][1], "Sample text");
        $this->assertEquals($classAnnotation['param1'][2], true);
        $this->assertEquals($classAnnotation['param1'][3], false);

        $this->assertTrue(count($classAnnotation['param2']) == 3);
        $this->assertEquals($classAnnotation['param2']['type'], 'example');
        $this->assertEquals($classAnnotation['param2']['value'], 1);
        $this->assertEquals($classAnnotation['param2'][0], 12);

        $this->assertEquals($classAnnotation['param3'], 'some example string without quotation');

    }

    public function testGetFromProperty()
    {
        $annotation = new AnnotationReflection('SampleAnnotatedClass');
        $propertyAnnotation = $annotation->getFromProperty('sampleVar');

        print_r($propertyAnnotation);


        $this->assertArrayHasKey('Param', $propertyAnnotation);
        $this->assertEquals($propertyAnnotation['Param']['type'], 'enum');
        $this->assertEquals($propertyAnnotation['Param']['enum'], 'AVAIBLE, NOT_AVAIBLE, REMOVED');
        $this->assertEquals($propertyAnnotation['Param']['required'], true);

        $nullAnnotation = $annotation->getFromProperty('noDocCommentVar');
        $this->assertNull($nullAnnotation);

    }

    public function testGetFromMethod()
    {

    }


}

//annotated class below

/**
 * Some description of annotated class
 * multilined description with some code
 * example
 * <code>
 * <?php echo 'sample code example'
 * </code>
 *
 * @author sample@author.com
 * @param1(111,"Sample text", true, FALSE)
 * @param2(type=example, value=1, 12)
 * @param3('some example string without quotation')
 *
 */
class SampleAnnotatedClass
{
    /**
     *
     */
    public function sampleFunction()
    {

    }



    /**
     * This is description of sample var
     * @Param(type=enum, enum="AVAIBLE, NOT_AVAIBLE, REMOVED" , required=true)
     */
    protected $sampleVar;

    public $noDocCommentVar;

    /**
     * @var string
     * @SampleAnnotation(sample="property",'property')
     * @Sample2Annotation(var="definition")
     */
    private $noDescriptionVar;


}