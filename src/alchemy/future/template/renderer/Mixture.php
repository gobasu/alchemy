<?php
/**
 * Alchemy Framework (http://alchemyframework.org/)
 *
 * @link      http://github.com/dkraczkowski/alchemy for the canonical source repository
 * @copyright Copyright (c) 2012-2013 Dawid Kraczkowski
 * @license   https://raw.github.com/dkraczkowski/alchemy/master/LICENSE New BSD License
 */
namespace alchemy\future\template\renderer;
use alchemy\future\template\renderer\mixture\Tokenizer;
use alchemy\future\template\renderer\mixture\Parser;
use alchemy\future\template\renderer\mixture\Compiler;

class MixtureException extends \Exception {}
/**
 * Mixture templating engine is mixin of
 * mustashe and jinja templating systems
 *
 * It gathers the best parts of boft simplifies
 * and compound them into one not too much logic tpl
 */
class Mixture
{
    public function __construct($dir = null)
    {
        $this->dir = AL_APP_DIR . DIRECTORY_SEPARATOR . 'view' . DIRECTORY_SEPARATOR . 'tpl';
    }

    public function render($name, &$data = array())
    {
        $file = $this->dir . DIRECTORY_SEPARATOR . $name;
        try {
            $parser = new Parser(new Tokenizer($file));
        } catch (\Exception $e) {
            throw new MixtureException('Cannot load template file \'' . $file . '\'');
        }

        $compiler = new Compiler();
        $compiler->compile($parser->parse());

        print_r($compiler->source);


    }

    protected $dir;
}
