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
namespace alchemy\util;
class CLI
{
    static public function clearScreen()
    {
        print chr(27) . "[H" . chr(27) . "[2J";
    }

    static public function centeredOutput($string, $width = null, $foreground = null, $background = null)
    {
        if (!$width) {
            $width = 80;
        }
        $repair = 0;
        $length = strlen($string);
        $padding = floor(($width - $length) / 2);

        if ($padding * 2 + $length < $width) {
            $repair = 1;
        }

        $string = sprintf('%' . $padding . 's%' . $length . 's%' . ($padding + $repair) . 's', '', $string, '');
        self::output($string, $foreground, $background);
    }

    static public function multiLineCenteredOutput($string, $width = null, $foreground = null, $background = null)
    {
        if (!$width) {
            $width = 80;
        }

        $string = explode("\n", $string);
        foreach ($string as $s) {
            $repair = 0;
            $length = strlen($s);
            $padding = floor(($width - $length) / 2);

            if ($padding * 2 + $length < $width) {
                $repair = 1;
            }
            $s = sprintf('%' . $padding . 's%' . $length . 's%' . ($padding + $repair) . 's', '', $s, '');
            self::output($s, $foreground, $background);
            self::eol();

        }
    }

    static public function eol()
    {
        self::output(PHP_EOL);
    }

    static public function getCLIParameters()
    {
        $argv = $_SERVER['argv'];
        print_r($argv);
        array_shift($argv);
        $out = array();
        foreach ($argv as $arg) {
            if (substr($arg, 0, 2) == '--') {
                $eqPos = strpos($arg, '=');
                if ($eqPos === false) {
                    $key = substr($arg, 2);
                    $out[$key] = isset($out[$key]) ? $out[$key] : true;
                } else {
                    $key = substr($arg, 2, $eqPos - 2);
                    $out[$key] = substr($arg, $eqPos + 1);
                }
            } else if (substr($arg, 0, 1) == '-') {
                if (substr($arg, 2, 1) == '=') {
                    $key = substr($arg, 1, 1);
                    $out[$key] = substr($arg, 3);
                } else {
                    $chars = str_split(substr($arg, 1));
                    foreach ($chars as $char) {
                        $key = $char;
                        $out[$key] = isset($out[$key]) ? $out[$key] : true;
                    }
                }
            } else {
                $out[] = $arg;
            }
        }
        return $out;

    }

    static public function output($string, $foreground = null, $background = null)
    {
        $output = '';

        if ($foreground && isset(self::$foregroundList[$foreground])) {
            $output .= "\033[" . self::$foregroundList[$foreground] . 'm';
        }
        if ($background && isset(self::$backgroundList[$background])) {
            $output .= "\033[" . self::$backgroundList[$background] . 'm';
        }
        $output .= $string . "\033[0m";

        print $output;
    }

    protected static $backgroundList = array(
        'black' => '40',
        'red' => '41',
        'green' => '42',
        'yellow' => '43',
        'blue' => '44',
        'magenta' => '45',
        'cyan' => '46',
        'gray' => '47'
    );

    protected static $foregroundList = array(
        'black' => '0;30',
        'dark_gray' => '1;30',
        'blue' => '0;34',
        'light_blue' => '1;34',
        'green' => '0;32',
        'light_green' => '1;32',
        'cyan' => '0;36',
        'light_cyan' => '1;36',
        'red' => '0;31',
        'light_red' => '1;31',
        'purple' => '0;35',
        'light_purple' => '1;35',
        'brown' => '0;33',
        'yellow' => '1;33',
        'light_gray' => '0;37',
        'white' => '1;37'
    );
}