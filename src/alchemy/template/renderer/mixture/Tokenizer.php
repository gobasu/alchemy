<?php
/**
 *
 */
class Tokenizer
{
    public function scan($text)
    {
        $length = strlen($text);
        $buffer = '';
        $tokens = array();
        for ($i = 0; $i < $length; $i++) {
            switch ($this->state) {
                case self::S_TEXT:
                    $tag = $text[$i] . $text[$i + 1];
                    if (in_array($tag, self::$oTokens)) {
                        $tokens[] = array(
                            'type'  => self::T_OPEN,
                            ''
                        );
                        //continue with for and switch token to type
                        ++$i;
                        continue 2;
                    }
                    $buffer .= $text[$i];
                break;

                case self::S_TAG:

                break;

                case self::S_PARAM:

                break;

                case self::S_STRING:

                break;
            }
        }
    }

    protected $state = self::S_TEXT;
    protected $line = 0;
    protected $index = 0;

    //opening tokens
    protected static $oTokens = array(
        self::T_SECTION,
        self::T_BLOCK,
        self::T_LOOP,
        self::T_UNESCAPE,
        self::T_ESCAPE,
        self::T_I18N,
        self::T_IGNORE,
        self::T_END_SECTION
    );

    //tag types
    const T_SECTION = '{#';
    const T_BLOCK = '{%';
    const T_LOOP = '{@';
    const T_UNESCAPE = '&{';
    const T_ESCAPE = '${';
    const T_I18N = '_(';
    const T_CLOSE_I18N = ')';
    const T_END_SECTION = '{/';
    const T_MCLOSE = '}';
    const T_IGNORE = '{-';

    //token types
    const T_OPEN = 0;
    const T_TAG = 1;
    const T_CLOSE = 2;
    const T_NAME = 3;
    const T_PARAM = 4;
    const T_TEXT = 5;
    const T_STRING = 6;

    //states
    const S_TEXT = 0;
    const S_TAG = 1;
    const S_TAGNAME = 2;
    const S_PARAM = 3;
    const S_STRING = 4;
}
