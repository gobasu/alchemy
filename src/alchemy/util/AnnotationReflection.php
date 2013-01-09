<?php
/**
 * Copyright (C) 2012 Dawid Kraczkowski
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to
 * deal in the Software without restriction, including without limitation the
 * rights to use, copy, modify, merge, publish, distribute, sublicense, and/or
 * sell copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED,
 * INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR
 * A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT
 * HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF
 * CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE
 * OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */
namespace alchemy\util;
use alchemy\util\annotation\Parser;
/**
 * Annotation
 *
 * @author: lunereaper
 */

final class AnnotationReflection
{
    /**
     * Creates annotation reflection object
     *
     * @param mixed $class classname or object
     */
    public function __construct($class)
    {
        if (is_object($class)) {
            $class = get_class($class);
        }
        $this->reflectionClass = new \ReflectionClass($class);

        //reflect class annotation
        $this->classAnnotation = Parser::parse($this->reflectionClass->getDocComment());

        //reflect method annotation
        foreach ($this->reflectionClass->getMethods() as $m) {
            if ($m->getDeclaringClass()->getName() != $class) {
                continue;
            }
            $this->declaredMethods[] = $m->getName();
            $this->methodsAnnotation[$m->getName()] = Parser::parse($m->getDocComment());
        }

        //reflect properties annotation
        foreach ($this->reflectionClass->getProperties() as $p) {
            if ($p->getDeclaringClass()->getName() != $class) {
                continue;
            }
            $this->declaredProperties[] = $p->getName();
            $this->propertiesAnnotation[$p->getName()] = Parser::parse($p->getDocComment());
        }
    }

    /**
     * Gets declared methods in given class
     *
     * @return array
     */
    public function getDeclaredMethods()
    {
        return $this->declaredMethods;
    }

    /**
     * Gets declared properties in given class
     *
     * @return array
     */
    public function getDeclaredProperties()
    {
        return $this->declaredProperties;
    }

    /**
     * Gets class annotations
     *
     * @return array
     */
    public function getFromClass()
    {
        return $this->classAnnotation;
    }

    /**
     * Gets class method's annotation
     *
     * @param string $name method name
     * @return mixed
     */
    public function getFromMethod($name)
    {
        if (isset($this->methodsAnnotation[$name])) {
            return $this->methodsAnnotation[$name];
        }
    }

    /**
     * Gets class property's annotation
     *
     * @param string $name property name
     * @return mixed
     */
    public function getFromProperty($name)
    {
        if (isset($this->propertiesAnnotation[$name])) {
            return $this->propertiesAnnotation[$name];
        }
    }

    protected $reflectionClass;
    protected $classAnnotation = array();
    protected $methodsAnnotation = array();
    protected $propertiesAnnotation = array();

    private $declaredMethods = array();
    private $declaredProperties = array();
}
