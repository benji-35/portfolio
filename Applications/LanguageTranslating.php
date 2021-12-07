<?php
namespace Applications;

class LanguageTranslating {

    private static $defaultLanguage = "en";
    private static $pathLanguages = "public/languages";

    public function __construct() {}

    public static function getLanguageFromLang(string $key, string $lang, string $defaultResult="Not found language [l.11]"):string {
        $path = self::$pathLanguages . "/$lang.conf";
        return self::getValueConf($key, $path, $defaultResult);
    }

    public static function getlanguage(string $key, string $defaultResult="Not found language [l.16]"):string {
        if (!isset($_SESSION['lang'])) {
            $_SESSION['lang'] = self::$defaultLanguage;
        }
        return self::getLanguageFromLang($key, $_SESSION['lang'], $defaultResult);
    }

    private static function getConfigFileValue($key, $path, $defaultResult="Not foun configValue[l.23]") {
        $res = $defaultResult;
        if (file_exists($path) == false) {
            return $res;
        }
        $readed = "";
        $f = fopen($path, "r");
        $size = filesize($path);
        if ($size > 0) {
            $readed = fread($f, $size);
        }
        fclose($f);
        $lines = explode("\n", $readed);
        $found = false;
        foreach ($lines as $line) {
            if (!self::strStartWith($line, "#")) {
                $intels = explode("=", $line, 2);
                if (count($intels) > 1 && $intels[0] == $key) {
                    $res = $intels[1];
                    $found = true;
                }
            }
            if ($found == true) {
                break;
            }
        }
        return $res;
    }

    public static function getValueConf($key, $path, $defaultResult="Not foun configValue[l.52]") {
        return self::getConfigFileValue($key, $path, $defaultResult);
    }

    public static function strStartWith(string $str, string $toFound) {
        $size1 = strlen($str);
        $size2 = strlen($toFound);

        if ($size2 > $size1) {
            return false;
        }
        if ($size1 == $size2) {
            if ($str == $toFound) {
                return true;
            } else {
                return false;
            }
        }
        for ($i = 0; $i < $size2; $i++) {
            if ($str[$i] != $toFound[$i]) {
                return false;
            }
        }
        return true;
    }
}
?>