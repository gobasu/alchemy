<?php
namespace util\command;
use alchemy\util\CLI;
/**
 * Application Console Command Handler
 *
 * application:create
 */

class Application
{
    public static function create()
    {
        CLI::output('creating app dir...');
        CLI::output('creating public dir...');
        CLI::output('creating index file...');
    }
}
