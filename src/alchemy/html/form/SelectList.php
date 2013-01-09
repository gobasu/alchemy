<?php
namespace alchemy\html\form;
class SelectList extends Input
{
    public function setData($list)
    {
        $this->list = $list;
    }

    public function getData()
    {
        return $this->list;
    }

    public function __toString()
    {
        $selectValue = $this->getValue();
        $options = '';
        foreach ($this->list as $name => $value)
        {
            if (is_array($value))
            {
                $options .= '<optgroup label="' . $name . '">';
                foreach($value as $optgroupName => $optgroupValue)
                {
                    $options .= '<option ' . ($selectValue == $optgroupName ? 'selected="selected"' : '') . ' value="' . $optgroupName  . '">' . $optgroupValue . '</option>';
                }
                $options .= '</optgroup>';
                continue;
            }

            $options .= '<option ' . ($selectValue == $name ? 'selected="selected"' : '') . ' value="' . $name  . '">' . $value . '</option>';
        }
        //return '';
        return sprintf(self::TEMPLATE, $this->attributesToString('value'), $options);
    }

    private $list = array();
    const TEMPLATE = '<select %s>%s</select>';
}