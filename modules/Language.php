<?php
/**
 * Detects, parses and caches language strings.
 *
 * @author Christoph Jahn <chris@tritanium-scripts.com>
 * @copyright Copyright (c) 2010-2023 Tritanium Scripts
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/ Creative Commons 3.0 by-nc-sa
 * @package TBB1
 */
class Language
{
    use Singleton;

    /**
     * Codes of available languages.
     *
     * @var array Language codes
     */
    private array $availableLangs = [];

    /**
     * The most fitting language code based on user's preference.
     *
     * @var string Assigned language code to use for translations
     */
    private string $langCode;

    /**
     * Cached and parsed language strings.
     *
     * @see getString()
     * @var array Language strings
     */
    private array $langStrings = [];

    /**
     * Detects available localizations and chooses the best one based on detected user's preference.
     *
     * @return Language New instance of this class
     */
    function __construct()
    {
        if(file_exists('cache/Languages.cache.php'))
            include('cache/Languages.cache.php');
        else
        {
            foreach(glob('languages/*') as $curLangCode)
                $this->availableLangs[] = basename($curLangCode);
            if(Config::getInstance()->getCfgVal('use_file_caching') == 1)
                Functions::file_put_contents('cache/Languages.cache.php', '<?php $this->availableLangs = array(\'' . implode('\', \'', $this->availableLangs) . '\'); ?>', LOCK_EX, false, false);
        }
        $this->setPrefLang();
    }

    /**
     * Returns available languages by their code.
     *
     * @return array Lang codes of available translations
     */
    public function getAvailLangs(): array
    {
        return $this->availableLangs;
    }

    /**
     * Returns used language.
     *
     * @return string Used language code
     */
    public function getLangCode(): string
    {
        return $this->langCode;
    }

    /**
     * Detects preferred languages of current user reported by its browser.
     *
     * @return array Preferred language codes from current browser, sorted by priority
     * @link http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.4
     */
    private function getPrefLangs(): array
    {
        $prefLangs = [];
        foreach(Functions::explodeByComma(Functions::strtolower(isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? $_SERVER['HTTP_ACCEPT_LANGUAGE'] : Config::getInstance()->getCfgVal('lng_folder'))) as $value) #de-de,de;q=0.8,en-us;q=0.5,en;q=0.3
            $prefLangs[(count($value = explode(';', $value)) == 1 || !preg_match('/q=([\d.]+)/i', $value[1], $quality) ? '1.0' : $quality[1]) . mt_rand(0, 9999)] = $value[0];
        krsort($prefLangs);
        return array_map(function($code)
        {
            return Functions::strpos($code, '-') === false ? $code : Functions::substr($code, 0, 3) . Functions::strtoupper(Functions::substr($code, 3));
        }, array_values($prefLangs));
    }

    /**
     * Returns a translated language string for stated index. Automatically parses a needed file, if provided.
     *
     * @param string $index Identifier of translated string
     * @param string $file Optional name of language INI file containing this identifier
     * @return string|bool Localized string or false if identifier was not found
     */
    public function getString(string $index, ?string $file=null)
    {
        return isset($this->langStrings[$this->langCode][$index]) || (!empty($file) && $this->parseFile($file) && isset($this->langStrings[$this->langCode][$index])) ? $this->langStrings[$this->langCode][$index] : !trigger_error('Identifier \'' . $index . '\' for ' . $this->langCode . ' not found', E_USER_NOTICE);
    }

    /**
     * Returns all loaded strings for current language.
     *
     * @return array Reference to current loaded language strings
     */
    public function &getStrings(): array
    {
        return $this->langStrings[$this->langCode];
    }

    /**
     * Parses a language file and adds its contents to cached strings.
     *
     * @param string $file Name of language INI file
     * @return bool File contents being cached
     */
    public function parseFile(string $file): bool
    {
        //Already loaded?
        if(isset($this->langStrings[$this->langCode][$file]))
            return true;
        $cacheFile = 'cache/Language-' . $this->langCode . '-' . $file . '.cache.php';
        $iniFile = 'languages/' . $this->langCode . '/' . $file . '.ini';
        //Already parsed?
        if(file_exists($cacheFile) && (filemtime($cacheFile) > filemtime($iniFile)))
        {
            include($cacheFile);
            return true;
        }
        if(!file_exists($iniFile))
            return !trigger_error('Language file ' . $iniFile . ' does not exist!', E_USER_NOTICE);
        //Parse file and add to strings
        $toCache = [];
        foreach(parse_ini_file($iniFile) as $curKey => $curString)
            $toCache[] = '$this->langStrings[\'' . $this->langCode . '\'][\'' . $curKey . '\'] = \'' . addcslashes(($this->langStrings[$this->langCode][$curKey] = $curString), '\'') . '\';';
        //Cache file
        if(Config::getInstance()->getCfgVal('use_file_caching') == 1)
            Functions::file_put_contents($cacheFile, '<?php ' . implode("\n", $toCache) . ' ?>', LOCK_EX, false, false);
        return ($this->langStrings[$this->langCode][$file] = true);
    }

    /**
     * Sets most fitting language or native one on no matching.
     */
    private function setPrefLang(): void
    {
        foreach(($prefLangs = $this->getPrefLangs()) as $curPrefLang)
            if(in_array($curPrefLang, $this->availableLangs))
            {
                $this->langCode = $curPrefLang;
                return;
            }
        //Second attempt to detect language at a more general / less strict matching level, e.g. "de-DE" is valid if only "de" was detected
        if(empty($this->langCode))
        {
            foreach($prefLangs as $curPrefLang)
                foreach($this->availableLangs as $curLangCode)
                    if(substr($curLangCode, 0, 2) == $curPrefLang)
                    {
                        $this->langCode = $curLangCode;
                        return;
                    }
            //No match: Set native code
            if(empty($this->langCode))
                $this->langCode = Config::getInstance()->getCfgVal('lng_folder');
        }
    }
}
?>