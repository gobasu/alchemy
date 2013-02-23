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
use alchemy\future\template\renderer\mixture\Template;

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
        if (!$dir) {
            $this->dir = AL_APP_DIR . DIRECTORY_SEPARATOR . 'view' . DIRECTORY_SEPARATOR . 'tpl';
        } else {
            $this->dir = realpath($dir);
        }
        $this->cache = sys_get_temp_dir();
    }

    public function setCacheDir($dir)
    {
        $dir = realpath($dir);
        if (!is_dir($dir)) {
            throw new MixtureException('Cache dir ' . $dir . ' does not exists');
        }
        $this->cache = $dir;
    }

    public function render($name, &$data = array())
    {
        Template::setCacheDir($this->cache);
        Template::setTemplateDir($this->dir);
        $tpl = Template::factory($name, $data);
        return $tpl->render();

    }

    protected $dir;
    protected $cache;

}
