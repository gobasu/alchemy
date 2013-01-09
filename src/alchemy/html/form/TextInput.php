<?php
namespace alchemy\html\form;

class TextInput extends Input
{
    public function __toString()
    {
        return sprintf(self::TEMPLATE, $this->attributesToString());
    }

    const TEMPLATE = '<input type="text" %s />';
}