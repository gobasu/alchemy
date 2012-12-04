<?php
namespace alchemy\storage\db;

use alchemy\storage\db\EntityException;
use alchemy\util\AnnotationReflection;
use alchemy\app\Loader;

class SchemaBuilderException extends \Exception {}
/**
 * SchemaBuilder
 */
class SchemaBuilder
{
    /**
     * Gets Schema for given Entity classname
     * If Schema Class is valid will load cached class
     * otherwise new Schema will be generated and instance
     * of this class will be returned
     *
     * @param string $className
     * @return ISchema
     */
    public static function getSchema($className)
    {
        $schemaBuilder = new SchemaBuilder($className);
        $path = Loader::getPathForApplicationClass($className);
        $schemaPath = AL_APP_CACHE_DIR . '/' . sha1($path) . '.php';

        if (is_readable($schemaPath)) {
            if (filemtime($schemaPath) >= filemtime(Loader::getPathForApplicationClass($className))) {
                //get the schema from cache
                require_once $schemaPath;
                return $schemaBuilder->getInstance();
            }
        }

        //build schema
        $schemaBuilder->build();
        $schemaBuilder->save($schemaPath);
        return $schemaBuilder->getInstance();
    }


    protected function __construct($className)
    {
        $this->className = $className;
        $this->schemaClassName = $className . self::SCHEMA_CLASS_POSTFIX;
    }

    protected function build()
    {
        $annotationReflection = new AnnotationReflection($this->className);

        $classAnnotations = $annotationReflection->getFromClass();
        $propertyList = $annotationReflection->getDeclaredProperties();

        //get PK for Entity
        if (!isset($classAnnotations[self::ANNOTATION_PK])) {
            throw new EntityException('Missing @' . self::ANNOTATION_PK . ' annotation in ' . $this->className . ' definition');
        }

        if (!isset($classAnnotations[self::ANNOTATION_COLLECTION])) {
            throw new EntityException('Missing @' . self::ANNOTATION_COLLECTION . ' annotation in ' . $this->className . ' definition');
        }

        $pk = $classAnnotations[self::ANNOTATION_PK];
        $collection = $classAnnotations[self::ANNOTATION_COLLECTION];
        $constructBody = '';
        $externalProperties = array();
        $internalProperties = array();


        foreach ($propertyList as $propertyName) {
            $propertyAnnotation = $annotationReflection->getFromProperty($propertyName);
            //ommit properties without @Property annotation
            if (!isset($propertyAnnotation[self::ANNOTATION_PROPERTY])) {
                continue;
            }
            $annotation = $propertyAnnotation[self::ANNOTATION_PROPERTY];

            $internalProperties[] = $propertyName;
            //add property to schema
            $property = '        $this->propertyList[\'' . $propertyName . '\']';
            if (isset($annotation[self::PROPERTY_ATTRIBUTE_NAME])) {
                $externalProperties[] = $annotation[self::PROPERTY_ATTRIBUTE_NAME];
                $constructBody .= PHP_EOL . $property . ' = new \alchemy\storage\db\Property(\'' . $annotation[self::PROPERTY_ATTRIBUTE_NAME] . '\');';
            } else {
                $externalProperties[] = $propertyName;
                $constructBody .= PHP_EOL . $property . ' = new \alchemy\storage\db\Property(\'' . $propertyName . '\');';
            }

            if (!isset($annotation[self::PROPERTY_ATTRIBUTE_TYPE])) {
                throw new EntityException('Missing attribute `' . self::PROPERTY_ATTRIBUTE_TYPE . '` in @' . self::ANNOTATION_PROPERTY . ' annotation used at ' . $this->className . '::$' . $propertyName);
            }

            //set property type
            if (isset(self::$typeMap[$annotation[self::PROPERTY_ATTRIBUTE_TYPE]])) {
                $constructBody .= PHP_EOL . $property . '->setType(' . self::$typeMap[$annotation[self::PROPERTY_ATTRIBUTE_TYPE]] . ');';
            } else {
                $constructBody .= PHP_EOL . $property . '->setType(' . self::$typeMap['default'] . ');';
            }

            //is property required
            if (isset($annotation[self::PROPERTY_ATTRIBUTE_REQUIRED])) {
                $constructBody .= PHP_EOL . $property . '->setRequired();';
            }


        }
        $externalProperties = 'array(\'' . implode('\',\'', $externalProperties) .  '\')';
        $internalProperties = 'array(\'' . implode('\',\'', $internalProperties) .  '\')';

        $className = explode('\\', $this->className);
        $namespace = implode('\\', array_slice($className,0, -1));
        $className = array_slice($className, -1);
        $className = $className[0] . self::SCHEMA_CLASS_POSTFIX;
        $this->schemaData = sprintf(self::CLASS_TEMPLATE, $namespace, $className, $constructBody, '\'' . $pk . '\'', '\'' . $collection . '\'', $externalProperties, $internalProperties);

        eval($this->schemaData);
    }

    protected function getInstance()
    {
        return new $this->schemaClassName;
    }

    protected function save($filename)
    {
        if ((is_file($filename) && !is_writable($filename)) || !is_writable(dirname($filename)))  {
            throw new SchemaBuilderException('File ' . $filename . ' is not writeable');
        }
        file_put_contents($filename,'<?php' . PHP_EOL . $this->schemaData);
    }

    /**
     * @var string
     */
    protected $className;
    protected $schemaClassName;
    protected $schemaData;

    protected static $typeMap = array(
        'string'    => Property::TYPE_STRING,
        'text'      => Property::TYPE_STRING,
        'int'       => Property::TYPE_NUMBER,
        'float'     => Property::TYPE_NUMBER,
        'number'    => Property::TYPE_NUMBER,
        'date'      => Property::TYPE_DATE,
        'enum'      => Property::TYPE_ENUM,
        'bool'      => Property::TYPE_BOOL,
        'boolean'   => Property::TYPE_BOOL,
        'blob'      => Property::TYPE_BLOB,
        'default'   => Property::TYPE_STRING
    );

    const ANNOTATION_PK = 'Pk';
    const ANNOTATION_PROPERTY = 'Param';
    const ANNOTATION_CONNECTION = 'Connection';
    const ANNOTATION_COLLECTION = 'Collection';

    const PROPERTY_ATTRIBUTE_NAME = 'name';
    const PROPERTY_ATTRIBUTE_REQUIRED = 'required';
    const PROPERTY_ATTRIBUTE_TYPE = 'type';

    const SCHEMA_CLASS_POSTFIX = 'Schema';

    const CLASS_TEMPLATE = <<<CLASS
namespace %s;
/**
 * Class generated automatically via \alchemy\storage\db\SchemaBuilder
 * DO NOT CHANGE THIS MANUALLY
 */
final class %s implements \alchemy\storage\db\ISchema, \Iterator
{
    public function __construct()
    {
        %s
    }
    public function getPK()
    {
        return \$this->pk;
    }

    public function getPropertyNameList(\$external = false)
    {
        if (\$external) {
            return \$this->externalPropertyNameList;
        } else {
            return \$this->internalPropertyNameList;
        }
    }

    public function __get(\$name)
    {
        return \$this->propertyList[\$name];
    }

    public function getPKProperty()
    {
        return \$this->propertyList[\$this->getPK()];
    }

    public function getPropertyType(\$name)
    {
        return \$this->propertyList[\$name]->getType();
    }
    public function getCollection()
    {
        return \$this->collectionName;
    }
    public function rewind()
    {
        reset(\$this->propertyList);
    }
    public function current()
    {
        return current(\$this->propertyList);
    }
    public function key()
    {
        return key(\$this->propertyList);
    }
    public function next()
    {
        return next(\$this->propertyList);
    }
    public function valid()
    {
        \$key = key(\$this->propertyList);
        return \$key !== NULL && \$key !== FALSE;
    }

    protected \$pk = %s;
    protected \$propertyList = array();
    protected \$collectionName = %s;
    protected \$instance;
    protected \$externalPropertyNameList = %s;
    protected \$internalPropertyNameList = %s;
}
CLASS;

}
