<?php
namespace alchemy\html\form;
class Password extends Input
{
    public function __toString()
    {
        return sprintf(self::TEMPLATE, $this->attributesToString());
    }


    const TEMPLATE = '<input type="password" %s />';
}