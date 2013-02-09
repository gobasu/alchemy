<?php
use alchemy\future\template\html\Form;
use alchemy\future\template\html\form\TextInput;

class FormTest extends PHPUnit_Framework_TestCase
{
    public function testFormCreation()
    {
        $form = new Form('sample');
        $this->assertRegExp('#\<input\s+type\=\"hidden\"\s+name\=\"[a-z0-9]+\"\s+value\=\"1\"\s+\/\>#is', '' . $form);
    }

    public function testAddItem()
    {
        $form = new Form('sample');
        $form->inputName = new TextInput('label');
        $form->inputName2 = new TextInput('label2');

        $data = array('inputName' => 'label', 'inputName2' => 'label2');

        foreach ($form as $name => $input) {
            $this->assertEquals($data[$name], $input->getLabel());
            $this->assertRegExp('#\<input\s+type\=\"text\"\s+name\=\"[a-z0-9]+\"\s+\/\>#is', '' . $input);
        }
    }

}