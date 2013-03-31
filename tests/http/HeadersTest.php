<?php
use alchemy\http\Headers;
class HeadersTest extends PHPUnit_Framework_TestCase
{
    public function testParseAccept()
    {
        $accept = 'text/*;q=0.3, text/html;q=0.7, text/html;level=1,
               text/html;level=2;q=0.4, */*;q=0.5';
        $parsed = Headers::parseAccept($accept);

        $expected = array(
            array(
                'type'  => 'text/html',
                'level' => 1,
                'q'     => 1
            ),
            array(
                'type'  => 'text/html',
                'q'     => 0.7
            ),
            array(
                'type'  => '*/*',
                'q'     => 0.5
            ),
            array(
                'type'  => 'text/html',
                'level' => 2,
                'q'     => 0.4
            ),
            array(
                'type'  => 'text/*',
                'q'     => 0.3
            )
        );

        $this->assertEquals($expected, $parsed);
    }

}