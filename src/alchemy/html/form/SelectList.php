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