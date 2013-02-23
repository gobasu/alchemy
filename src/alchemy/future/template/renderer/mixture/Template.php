<?php
/**
 * Alchemy Framework (http://alchemyframework.org/)
 *
 * @link      http://github.com/dkraczkowski/alchemy for the canonical source repository
 * @copyright Copyright (c) 2012-2013 Dawid Kraczkowski
 * @license   https://raw.github.com/dkraczkowski/alchemy/master/LICENSE New BSD License
 */
namespace alchemy\future\template\renderer\mixture;
use alchemy\future\template\renderer\MixtureException;

class TemplateException extends MixtureException {}

class Template
{
    protected function __construct(&$data = array())
    {
        if ($data instanceof VarStack) {
            $this->stack = $data;
        } else {
            $this->stack = new VarStack($data);
        }
    }

    public function render()
    {
        return '';
    }

    public function import($name)
    {
        self::factory($name, $this->stack)->render();
    }

    public static function setTemplateDir($dir)
    {
        self::$templateDir = $dir;
    }

    public static function setCacheDir($dir)
    {
        self::$cacheDir = $dir;
    }

    public static function getTemplateFileName($name)
    {
        return self::$templateDir . DIRECTORY_SEPARATOR . $name;
    }

    /**
     * @param $name
     * @return Template
     */
    public static function factory($name, &$data)
    {
        self::load($name);
        $templateFileName = self::getTemplateFileName($name);
        $templateClassName = Compiler::getTemplateClassName($templateFileName);
        return new $templateClassName($data);
    }

    /**
     * Loads dependency templates
     * @param $name template name
     */
    public static function load($name)
    {
        $templateFileName = self::getTemplateFileName($name);

        if (!is_readable($templateFileName)) {
            throw new TemplateException('Template file:' . $templateFileName . ' is not readable');
        }
        $templateClassName = Compiler::getTemplateClassName($templateFileName);

        //template was already loaded
        if (class_exists($templateClassName)) {
            return true;
        }

        //template from cache
        $templateCacheFileName = self::$cacheDir . DIRECTORY_SEPARATOR . $templateClassName . '.php';
        if (is_readable($templateCacheFileName) && filemtime($templateCacheFileName) >= filemtime($templateFileName)) {
            require_once $templateCacheFileName;
            return true;
        }

        //parse template
        try {
            $parser = new Parser(new Tokenizer($templateFileName));
        } catch (\Exception $e) {
            throw new TemplateException('Unexpected error occured while loading your template file');
        }
        $compiler = new Compiler();
        $compiler->compile($parser->parse());

        //save cache & return new template object
        file_put_contents($templateCacheFileName, $compiler->getOutput($templateClassName));
        require_once $templateCacheFileName;

        return true;
    }

    /**
     * @var VarStack
     */
    protected $stack;

    protected static $templateDir;

    protected static $cacheDir;
}
