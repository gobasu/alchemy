<?php
use alchemy\security\Validator;
/**
 * Validator
 */
class ValidatorTest extends PHPUnit_Framework_TestCase
{
    public function testCreditCard()
    {
        $this->assertTrue(Validator::creditCard('4539079698207285'));
        $this->assertTrue(Validator::creditCard('4929789221388539', Validator::CC_TYPE_VISA));
        $this->assertFalse(Validator::creditCard('4929789221388539', Validator::CC_TYPE_MASTERCARD));
        $this->assertTrue(Validator::creditCard('5428624765466362', Validator::CC_TYPE_MASTERCARD));
        $this->assertTrue(Validator::creditCard('5368385794876060'));
    }

    public function testNumber()
    {
        $this->assertTrue(Validator::number(12));
        $this->assertTrue(Validator::number('12'));
        $this->assertTrue(Validator::number('12.41'));
        $this->assertFalse(Validator::number('1sddfs2.41'));
        $this->assertTrue(Validator::number('10', array('min_value' => 2, 'max_value' => 10)));
        $this->assertFalse(Validator::number('1', array('min_value' => 2, 'max_value' => 10)));
    }

    public function testString()
    {
        $this->assertTrue(Validator::string('sample string'));
        $this->assertTrue(Validator::string('sample string', array('min_length' => 10, 'max_length' => 20)));
        $this->assertFalse(Validator::string('sample', array('min_length' => 10, 'max_length' => 20)));
        $this->assertFalse(Validator::string('sample string higher than 20 characters', array('min_length' => 10, 'max_length' => 20)));
        $this->assertFalse(Validator::string(11));
    }

    public function testEmail()
    {
        $this->assertTrue(Validator::email('sample@email.com'));
        $this->assertFalse(Validator::email('sampleemail.com'));
    }

    public function testUrl()
    {
        $this->assertTrue(Validator::url('http://google.com'));
        $this->assertFalse(Validator::url('google.com'));
    }

    public function testIp()
    {
        $this->assertTrue(Validator::ip('123.123.123.123'));
        $this->assertFalse(Validator::ip('not an ip'));
        $this->assertFalse(Validator::ip('300.300.300.256'));
        $this->assertFalse(Validator::ip('256.100.300.256'));
    }

    public function testDate()
    {
        $this->assertTrue(Validator::date('11-11-11'));
        $this->assertTrue(Validator::date('12-12-12'));
        $this->assertFalse(Validator::date('12-13-12'));
        $this->assertTrue(Validator::date('12/12/12'));
        $this->assertTrue(Validator::date('12.12.12'));
        $this->assertTrue(Validator::date('12.12.12', array('format' => 'd.m.y')));
        $this->assertFalse(Validator::date('12.12.12', array('format' => 'd.m.Y')));
        $this->assertTrue(Validator::date('12.12.12', array('format' => 'd.m.y', 'min_range' => '10.11.11', 'max_range' => '12.12.12')));
        $this->assertFalse(Validator::date('09.10.11', array('format' => 'd.m.y', 'min_range' => '10.11.11', 'max_range' => '12.12.12')));
        $this->assertFalse(Validator::date('13.12.12', array('format' => 'd.m.y', 'min_range' => '10.11.11', 'max_range' => '12.12.12')));
    }
}
