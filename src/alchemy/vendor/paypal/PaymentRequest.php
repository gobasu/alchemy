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
namespace alchemy\vendor\paypal;

class PaymentRequest
{
    public function __construct($value, $desc, $paymentId)
    {
        $this->requestId = self::$paymentId++;

        $this->value = $value;
        $this->desc = $desc;
        $this->id = $paymentId;
    }


    public function setShippingAddress($name, $street, $city, $zip, $state = null, $country = 'USA')
    {
        $this->address = array(
            'SHIPTONAME'            => $name,
            'SHIPTOSTREET'          => $street,
            'SHIPTOCITY'            => $city,
            'SHIPTOZIP'             => $zip,
            'SHIPTOSTATE'           => $state,
            'SHIPTOCOUNTRYCODE'     => $country,
            'ADDROVERRIDE'          => 1
        );
    }

    /**
     * Sets PayPal class
     * Package's method
     *
     * @param \alchemy\vendor\PayPal $p
     */
    public function _setPayPal(\alchemy\vendor\PayPal $p)
    {
        $this->paypalObject = $p;
    }

    public function addItem($value, $name, $qty = 1, $id = null, $desc = null)
    {
        $requestId = 'L_PAYMENTREQUEST_' . $this->requestId . '_';
        $itemId = $this->itemId++;
        $value = number_format( (float)$value, 2);
        $this->itemTotalValue += $value * $qty;
        $this->items[] = array(
            $requestId . 'NAME'     . $itemId   => $name,
            $requestId . 'AMT'      . $itemId   => $value,
            $requestId . 'QTY'      . $itemId   => $qty,
            $requestId . 'NUMBER'   . $itemId   => $id,
            $requestId . 'DESC'     . $itemId   => $desc
        );
    }

    public function serialize()
    {
        $result = array(

            'PAYMENTREQUEST_' . $this->requestId . '_AMT' => &$this->value,
            'PAYMENTREQUEST_' . $this->requestId . '_DESC' => $this->desc,
            'PAYMENTREQUEST_' . $this->requestId . '_CURRENCYCODE' => $this->paypalObject->getPaymentCurrency(),
            'PAYMENTREQUEST_' . $this->requestId . '_PAYMENTACTION' => 'Sale'
        );

        if (!empty($this->address)) $result = array_merge($result, $this->address);
        else $result['NOSHIPPING'] = 1;

        //parse items if exists
        if (empty($this->items)) return $result;
        $this->value = $this->itemTotalValue;
        foreach ($this->items as $i)
        {
            $result = array_merge($result, $i);
        }

        return $result;

    }

    private $address = array();

    private $paypalObject;
    protected $desc;
    protected $value;
    protected $id;
    protected $itemTotalValue = 0;
    protected $items = array();
    private $itemId = 0;
    private $requestId;
    protected static $paymentId = 0;
}