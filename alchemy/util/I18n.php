<?php
namespace alchemy\util;
use alchemy\http\Headers;

class I18nException extends \Exception {}

/**
 * Internationalization helper class, call after creating instance of Application class, otherwise
 * @example:
 * $i18n = new I18n();
 * $i18n->setDefaultLanguage('en_US');
 * $i18n->acceptFromHTTP();
 */
class I18n
{
    public function __construct()
    {
        if (!function_exists('gettext')) {
            throw new I18nException('Gettext extension is required to use this feature');
        }

    }

    /**
     * Adds domain
     * Required when you are creating instance of I18n before creating new Application
     *
     * @example
     * <code>
     * $i18n = new I18n();
     * $i18n->addDomain('messages', 'full/path/do/locale/dir');
     * </code>
     * Full path is not required when you've created application instance
     * <code>
     * $app = new alchemy\app\Application($appDir);
     * $i18n->addDomain('messages','path/relative/to/$appDir');
     * </code>
     *
     * @param string $domain
     * @param string $path
     * @throws I18nException
     */
    public function addDomain($domain = 'messages', $path = 'locale')
    {
        if (defined('AL_APP_DIR')) {
            $path = AL_APP_DIR . '/' . $path;
        }

        if (!is_dir($path)) {
            throw new I18nException('Could not add domain: `' . $path . '` does not exists');
        }
        bindtextdomain($domain, $path);
        if ($this->encoding) {
            bind_textdomain_codeset($domain, $this->encoding);
        }

        $this->registeredDomains[$domain] = $path;
    }

    /**
     * Switches between different domains
     *
     * @see http://php.net/textdomain
     * @param string $domain
     * @return string
     * @throws I18nException
     */
    public function useDomain($domain = 'messages')
    {
        if (!isset($this->registeredDomains[$domain])) {
            throw new I18nException('Before using `' . $domain . '` domain use ' . __CLASS__ . '::addDomain($domain, $path) method');
        }

        $this->currentDomain = $domain;
        return textdomain($domain);
    }

    /**
     * Sets default language, when given one will not be found
     * than this one will be used
     *
     * @param string $lang
     */
    public function setDefaultLanguage($lang = 'en')
    {
        $this->defaultLanguage = $lang;
    }

    /**
     * Returns default language
     *
     * @return string
     */
    public function getDefaultLanguage()
    {
        return $this->defaultLanguage;
    }

    /**
     * Sets language
     *
     * @param $lang
     */
    public function setLanguage($lang)
    {
        if ($this->isLanguageAvailable($lang)) {
            $this->setPHPEnvironment($lang);
        }
        else {
            $this->setPHPEnvironment($this->defaultLanguage);
        }
        return textdomain($this->currentDomain);
    }

    /**
     * Sets language to one accepted by client
     * If none will be available default will be used
     */
    public function acceptFromHTTP()
    {
        if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
            $langList = Headers::parseAccept($_SERVER['HTTP_ACCEPT_LANGUAGE']);
            foreach ($langList as $lang) {
                $lang = explode('-', $lang['type']);
                if ($lang[1]) {
                    $lang = strtolower($lang[0]) . '_' . strtoupper($lang[1]);
                } else {
                    $lang = strtolower($lang[0]);
                }
                if (!$this->isLanguageAvailable($lang)) {
                    continue;
                }
                $this->setPHPEnvironment($lang);
                return textdomain($this->currentDomain);
            }
        }
        $this->setPHPEnvironment($this->defaultLanguage);
        return textdomain($this->currentDomain);
    }

    /**
     * Checks whatever language is available in current domain
     *
     * @param string $lang
     * @return bool true if is available
     */
    public function isLanguageAvailable($lang)
    {
        if (empty($this->registeredDomains)) {
            $this->addDomain('messages', 'locale');
        }
        $path = $this->registeredDomains[$this->currentDomain];
        return is_dir($path . '/' . $lang);
    }

    protected function setPHPEnvironment($lang)
    {
        putenv("LANG=$lang");
        putenv("LANGUAGE=$lang");
        setlocale(LC_ALL, $lang . '.' . $this->encoding);
        setlocale(LC_NUMERIC, 'C');

    }

    public static $countryCodes = array(
        'AD'   =>  'Andorra',
        'AL'   =>  'Shqipëria',
        'AM'   =>  'Hayastan',
        'AT'   =>  'Österreich',
        'AZ'   =>  'Azerbaycan',
        'BA'   =>  'Bosna i Hercegovina',
        'BE'   =>  'Belgique, België',
        'BG'   =>  'Bulgaria',
        'BY'   =>  'Belarus',
        'CH'   =>  'Schweiz, Suisse, Svizzera, Svizra',
        'CS'   =>  'Srbija i Crna Gora',
        'CZ'   =>  'Česká republika',
        'CY'   =>  'Kýpros',
        'DE'   =>  'Deutschland',
        'DK'   =>  'Danmark',
        'EE'   =>  'Eesti',
        'ES'   =>  'España',
        'FI'   =>  'Suomi, Finland',
        'FR'   =>  'France',
        'GB'   =>  'United Kingdom',
        'US'   =>  'United States',
        'GE'   =>  'Sakartvelo',
        'GR'   =>  'Elláda',
        'HR'   =>  'Hrvatska',
        'HU'   =>  'Magyarország',
        'IE'   =>  'Ireland, Éire',
        'IL'   =>  'Yisra\'el',
        'IS'   =>  'Ísland',
        'IT'   =>  'Itàlia',
        'LT'   =>  'Lietuva',
        'LU'   =>  'Luxembourg, Luxemburg, Lëtzebuerg',
        'LV'   =>  'Latvija',
        'MA'   =>  'Al Maghreb',
        'MC'   =>  'Monaco',
        'MD'   =>  'Moldova',
        'ME'   =>  'Crna Gora',
        'MK'   =>  'Makedonija',
        'MT'   =>  'Malta',
        'NL'   =>  'Nederland, Nerderlân',
        'NO'   =>  'Norge',
        'PL'   =>  'Polska',
        'PT'   =>  'Portugal',
        'RO'   =>  'Romînia',
        'RS'   =>  'Srbija',
        'RU'   =>  'Rossija',
        'SE'   =>  'Sverige',
        'SI'   =>  'Slovenija',
        'SK'   =>  'Slovensko',
        'SM'   =>  'San Marino',
        'TR'   =>  'Türkiye',
        'UA'   =>  'Ukraïna',
        'YU'   =>  'Jugoslavija'
    );

    protected $defaultLanguage = 'en';
    protected $currentDomain = 'messages';
    protected $registeredDomains = array();
    protected $encoding = 'UTF-8';
}
