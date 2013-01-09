<?php
use alchemy\http\Headers;
class HeadersTest extends PHPUnit_Framework_TestCase
{
    public function testParseAccept()
    {
        $accept = 'text/*;q=0.3, text/html;q=0.7, text/html;level=1,
               text/html;level=2;q=0.4, */*;q=0.5';
        print_r(Headers::parseAccept($accept));
    }

}