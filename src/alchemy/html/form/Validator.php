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
use alchemy\security\Validator as BaseValidator;
class ValidatorException extends \Exception {}
class UnknownValidatorException extends ValidatorException {}
class Validator
{
    /**
     * Creates validator object with options
     *
     * $options:
     *  - `error_msg` error message to display when data is invalid
     * more options:
     * @see alchemy\security\Validator
     *
     * @param $type
     * @param array $options
     */
    public function __construct($type, array $options = null)
    {
        if (!isset(self::$validatorList[$type])) {
            throw new UnknownValidatorException('Unknown validator type: ' . $type);
        }
        $this->options = $options;

        if (isset($options['error_msg'])) {
            $this->message = $options['error_msg'];
            unset($options['error_msg']);
        }
    }

    public function validate($input)
    {
        BaseValidator::validate($input, $this->type, $this->options);
    }

    public function setMessage($msg)
    {
        $this->message = $msg;
    }

    public function getMessage()
    {
        return $this->message;
    }

    protected $type;
    protected $message;
    protected $options;

    protected static $validatorList = array(
        BaseValidator::VALIDATE_CREDITCARD  => 1,
        BaseValidator::VALIDATE_DATE        => 1,
        BaseValidator::VALIDATE_EMAIL       => 1,
        BaseValidator::VALIDATE_IP          => 1,
        BaseValidator::VALIDATE_NUMBER      => 1,
        BaseValidator::VALIDATE_REGEXP      => 1,
        BaseValidator::VALIDATE_STRING      => 1,
        BaseValidator::VALIDATE_URL         => 1
    );
}
