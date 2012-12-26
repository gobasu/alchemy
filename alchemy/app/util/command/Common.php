<?php
namespace util\command;
use alchemy\util\CLI;
use alchemy\app\Console;
final class Common extends \alchemy\app\Controller
{
    public static function error($input)
    {
        $input = Console::instance()->getInput();
        CLI::output('Uknown command `' . $input . '` to help press `help`', 'white','red');
        CLI::eol();
    }

    public static function close()
    {
        CLI::output('bye!');
        exit(0);
    }

    public static function help()
    {

        $welcome = <<<WELCOME
      Welcome to alchemy util toolset
      ===============================
WELCOME;
        CLI::eol();
        CLI::multiLineCenteredOutput($welcome, 80);
        CLI::output('Command list:');
        CLI::eol();
        CLI::output("\t" . sprintf('%-30s', '- application:create [name] '), 'red');
        CLI::output('creates bootstrap application in current working directory');
        CLI::eol();
        CLI::output("\t" . sprintf('%-30s', '- locale:generate'), 'red');
        CLI::output('generates locale\'s template for current working directory');

        CLI::eol();
    }
}
