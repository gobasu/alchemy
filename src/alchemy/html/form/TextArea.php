<?php
namespace alchemy\html\form;
class TextArea extends Input
{
    public function __toString()
    {
        return sprintf(self::TEMPLATE, $this->attributesToString('value'), htmlentities($this->value));
    }

    const TEMPLATE = '<textarea %s>%s</textarea>';
}