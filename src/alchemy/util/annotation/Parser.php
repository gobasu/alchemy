<?php
namespace alchemy\util\annotation;
/**
 * Parser
 *
 * @author: lunereaper
 */

final class Parser
{

    final public static function parse($string)
    {
        $string = trim(preg_replace('/\r?\n *|^\/\*\*/', ' ', $string));

        preg_match_all('#' . self::MATCH_ANNOTATION . '#is', $string, $annotations);

        if (!count($annotations[1])) {
            return null;
        }
        foreach ($annotations[2] as &$value) {
            $value = self::parseValue($value);
        }
        $result = array_combine($annotations[1], $annotations[2]);
        return $result;
    }

    final public static function parseValue($string)
    {
        if (empty($string)) {
            return true;
        }
        //normal dock block
        if ($string[0] != '(') {
            return trim($string);
        }
        //parse annotation value

        return self::parseAnnotationValue($string);
    }


    final protected static function parseAnnotationValue($string)
    {
        $len = strlen($string) - 1;
        $result = array();
        $var = null;
        $buffer = '';
        $quota = null;
        $state = self::ST_DEFAULT;
        for ($i = 1; $i < $len; $i++) {
            $c = $string[$i];

            switch ($state) {
                case self::ST_DEFAULT:
                    if (!$buffer && $c== ' ') {
                        continue 2;
                    }

                    switch ($c) {
                        case ' ':
                            continue 3;
                        case '=':
                            $var = $buffer;
                            $buffer = '';
                            $state = self::ST_VALUE;
                            continue 3;
                        case ',':
                            if (isset(self::$keywords[$buffer])) {
                                $buffer = self::$keywords[$buffer];
                            }
                            if (empty($var)) {
                                $result[] = $buffer;
                            } else {
                                $result[$var] = $buffer;
                            }
                            $var = null;
                            $buffer = '';
                            $quota = null;
                            continue 3;
                        case '"':
                        case '\'':
                            $quota = $c;
                            $state = self::ST_STRING;
                            continue 3;
                    }
                    break;
                case self::ST_VALUE:
                    if ($c == ',') {
                        if (isset(self::$keywords[$buffer])) {
                            $buffer = self::$keywords[$buffer];
                        }
                        if (empty($var)) {
                            $result[] = $buffer;
                        } else {
                            $result[$var] = $buffer;
                        }
                        $var = null;
                        $buffer = '';
                        $quota = null;
                        $state = self::ST_DEFAULT;
                        continue 2;
                    } elseif (($c == '"' || $c == '\'') && !$buffer) {
                        $quota = $c;
                        $state = self::ST_STRING;
                        continue 2;
                    }
                    break;
                case self::ST_STRING:
                    if ($quota == $c) {
                        $state = self::ST_DEFAULT;
                        continue 2;
                    }
                    break;
            }
            $buffer .= $c;
        }
        if ($buffer) {
            if (isset(self::$keywords[$buffer])) {
                $buffer = self::$keywords[$buffer];
            }

            //there was only one parameter set in annotation so
            //do not make it an array
            if (empty($result) && !$var) {
                $result = $buffer;
            } else {
                if ($var) {
                    $result[$var] = $buffer;
                } else {
                    $result[] = $buffer;
                }
            }
        }

        return $result;
    }

    private  static $keywords = array(
        'false' => false,
        'FALSE' => false,
        'true'  => true,
        'TRUE'  => true
    );

    const ST_DEFAULT = 0;
    const ST_VALUE = 1;
    const ST_STRING = 2;
    const MATCH_ANNOTATION = '@([a-z0-9_]+)(.*?)\s+\*';
}
