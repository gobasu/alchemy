<?php
namespace alchemy\html\form;

class Hidden extends Input
{
    public function __toString()
    {
        return sprintf(self::TEMPLATE, $this->attributesToString());
    }

    const TEMPLATE = '<input type="hidden" %s />';
}