<?php
namespace alchemy\html\form;
class CheckBox extends Input
{
    public function __construct($label = '', Validator $validator = null)
    {
        parent::__construct($label, $validator);
    }

    public function __toString()
    {
        return sprintf(self::TEMPLATE, ($this->value == $this->chckValue ? 'checked="checked"' : ''), $this->chckValue, $this->attributesToString('value'));
    }


    const TEMPLATE = '<input type="checkbox" %s value="%s" %s />';
    private $chckValue = 1;
}