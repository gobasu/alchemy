<?php
use alchemy\template\Mixture;
use \alchemy\template\mixture\Tokenizer;
class TokenizerTest extends PHPUnit_Framework_TestCase
{
    public function testScan()
    {
        $file = ASSETS_DIR . '/tpl_tokenizer_test.html';
        $tokenizer = new Tokenizer($file);
        $tokens = $tokenizer->scan();

        foreach ($tokens as $index => $token) {
            $this->assertEquals($this->tokens[$index], $token);
        }

    }

    protected $tokens = array (
        0 =>
        array (
            'type' => 'text',
            'value' => '',
            'column' => 1,
            'line' => 0,
            'index' => 0,
        ),
        1 =>
        array (
            'type' => '${',
            'value' => '${',
            'column' => 1,
            'line' => 0,
            'index' => 0,
        ),
        2 =>
        array (
            'type' => 'param',
            'value' => 'var',
            'column' => 5,
            'line' => 0,
            'index' => 5,
        ),
        3 =>
        array (
            'type' => '}',
            'value' => '}',
            'column' => 5,
            'line' => 0,
            'index' => 5,
        ),
        4 =>
        array (
            'type' => 'text',
            'value' => '
',
            'column' => 1,
            'line' => 1,
            'index' => 7,
        ),
        5 =>
        array (
            'type' => '${',
            'value' => '${',
            'column' => 1,
            'line' => 1,
            'index' => 7,
        ),
        6 =>
        array (
            'type' => 'param',
            'value' => 'composed.var',
            'column' => 14,
            'line' => 1,
            'index' => 21,
        ),
        7 =>
        array (
            'type' => '}',
            'value' => '}',
            'column' => 14,
            'line' => 1,
            'index' => 21,
        ),
        8 =>
        array (
            'type' => 'text',
            'value' => '
',
            'column' => 1,
            'line' => 2,
            'index' => 23,
        ),
        9 =>
        array (
            'type' => '${',
            'value' => '${',
            'column' => 1,
            'line' => 2,
            'index' => 23,
        ),
        10 =>
        array (
            'type' => 'param',
            'value' => 'var',
            'column' => 5,
            'line' => 2,
            'index' => 28,
        ),
        11 =>
        array (
            'type' => 'param',
            'value' => 'with',
            'column' => 10,
            'line' => 2,
            'index' => 33,
        ),
        12 =>
        array (
            'type' => 'param',
            'value' => '1',
            'column' => 12,
            'line' => 2,
            'index' => 35,
        ),
        13 =>
        array (
            'type' => 'param',
            'value' => 'parameter',
            'column' => 23,
            'line' => 2,
            'index' => 46,
        ),
        14 =>
        array (
            'type' => '}',
            'value' => '}',
            'column' => 24,
            'line' => 2,
            'index' => 47,
        ),
        15 =>
        array (
            'type' => 'text',
            'value' => '
',
            'column' => 1,
            'line' => 3,
            'index' => 49,
        ),
        16 =>
        array (
            'type' => '{%',
            'value' => '{%',
            'column' => 1,
            'line' => 3,
            'index' => 49,
        ),
        17 =>
        array (
            'type' => 'param',
            'value' => 'empty-block',
            'column' => 14,
            'line' => 3,
            'index' => 63,
        ),
        18 =>
        array (
            'type' => '%}',
            'value' => '%}',
            'column' => 15,
            'line' => 3,
            'index' => 64,
        ),
        19 =>
        array (
            'type' => 'text',
            'value' => '
string
',
            'column' => 1,
            'line' => 5,
            'index' => 74,
        ),
        20 =>
        array (
            'type' => '{%',
            'value' => '{%',
            'column' => 1,
            'line' => 5,
            'index' => 74,
        ),
        21 =>
        array (
            'type' => 'param',
            'value' => 'empty-block',
            'column' => 14,
            'line' => 5,
            'index' => 88,
        ),
        22 =>
        array (
            'type' => 'param',
            'value' => 'with',
            'column' => 19,
            'line' => 5,
            'index' => 93,
        ),
        23 =>
        array (
            'type' => 'param',
            'value' => '1',
            'column' => 21,
            'line' => 5,
            'index' => 95,
        ),
        24 =>
        array (
            'type' => 'param',
            'value' => 'parameter',
            'column' => 32,
            'line' => 5,
            'index' => 106,
        ),
        25 =>
        array (
            'type' => '%}',
            'value' => '%}',
            'column' => 34,
            'line' => 5,
            'index' => 108,
        ),
        26 =>
        array (
            'type' => 'text',
            'value' => '
',
            'column' => 1,
            'line' => 6,
            'index' => 111,
        ),
        27 =>
        array (
            'type' => '{%',
            'value' => '{%',
            'column' => 1,
            'line' => 6,
            'index' => 111,
        ),
        28 =>
        array (
            'type' => 'param',
            'value' => 'to-left',
            'column' => 9,
            'line' => 6,
            'index' => 120,
        ),
        29 =>
        array (
            'type' => '%}',
            'value' => '%}',
            'column' => 10,
            'line' => 6,
            'index' => 121,
        ),
        30 =>
        array (
            'type' => 'text',
            'value' => '
',
            'column' => 1,
            'line' => 7,
            'index' => 124,
        ),
        31 =>
        array (
            'type' => '{%',
            'value' => '{%',
            'column' => 1,
            'line' => 7,
            'index' => 124,
        ),
        32 =>
        array (
            'type' => 'param',
            'value' => 'to-right',
            'column' => 11,
            'line' => 7,
            'index' => 135,
        ),
        33 =>
        array (
            'type' => '%}',
            'value' => '%}',
            'column' => 12,
            'line' => 7,
            'index' => 136,
        ),
        34 =>
        array (
            'type' => 'text',
            'value' => '
',
            'column' => 1,
            'line' => 8,
            'index' => 139,
        ),
        35 =>
        array (
            'type' => '{!',
            'value' => '{!',
            'column' => 1,
            'line' => 8,
            'index' => 139,
        ),
        36 =>
        array (
            'type' => 'text',
            'value' => ' ignore tokens ',
            'column' => 17,
            'line' => 8,
            'index' => 156,
        ),
        37 =>
        array (
            'type' => '!}',
            'value' => '!}',
            'column' => 17,
            'line' => 8,
            'index' => 156,
        ),
    );
}