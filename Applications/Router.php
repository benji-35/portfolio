<?php
namespace Applications;

class Router {
    public function __construct() {}

    private static $PAGE_NOT_FOUND_INDEX = 0;
    private static $PAGE_CONNECT_INDEX = 1;

    private static $pathes = array(
        "php" => "public/pages/",
        "css" => "public/css/",
        "js" => "public/js/",
    );

    private static $pages = array(
        array(
            "name" => "notFound",
            "title" => "Erreur 404",
            "url" => array("pagenotfound"),
            "php" => "pageNotFound",
            "sessions_vars" => array(),
            "needConnection" => false,
            "css" => array("pageNotFound"),
            "js" => array(),
            "isPhp" => true,
            "icon" => "BDLogo.png",
        ),
        array(
            "name" => "connection",
            "title" => "Connection",
            "url" => array("connection"),
            "php" => "connection",
            "sessions_vars" => array(),
            "needConnection" => false,
            "css" => array("connection"),
            "js" => array("connection"),
            "isPhp" => true,
            "icon" => "BDLogo.png",
        ),
        array(
            "name" => "googleSearch",
            "title" => "Google [copie]",
            "url" => array("google"),
            "lang" => "fr",
            "php" => "googleSearch",
            "sessions_vars" => array(),
            "needConnection" => false,
            "css" => array("googleSearch"),
            "js" => array("googleSearch"),
            "isPhp" => true,
            "icon" => "googleLogo2.webp",
        ),
        array(
            "name" => "main",
            "title" => "Portfolio",
            "url" => array(""),
            "php" => "mainPage",
            "sessions_vars" => array(),
            "needConnection" => false,
            "css" => array("mainPage"),
            "js" => array("mainPage", "titleAnim"),
            "isPhp" => true,
            "icon" => "BDLogo.png",
        ),
        array(
            "name" => "mainHide",
            "title" => "Hide",
            "url" => array("hide"),
            "php" => "mainHide",
            "sessions_vars" => array(),
            "needConnection" => false,
            "css" => array("mainHide"),
            "js" => array("mainHide"),
            "isPhp" => true,
            "icon" => "BDLogo.png",
        ),
        array(
            "name" => "myCopies",
            "title" => "My Copies",
            "url" => array("copies"),
            "php" => "myCopies",
            "sessions_vars" => array(),
            "needConnection" => false,
            "css" => array("myCopies"),
            "js" => array(),
            "isPhp" => true,
            "icon" => "BDLogo.png",
        ),
        array(
            "name" => "discuss",
            "title" => "myDiscussionTitle",
            "url" => array("discuss"),
            "php" => "discuss",
            "sessions_vars" => array(),
            "needConnection" => true,
            "css" => array("discuss"),
            "js" => array("discuss"),
            "isPhp" => true,
            "translateTitle" => true,
            "icon" => "BDLogo.png",
        ),
        array(
            "name" => "youtube",
            "title" => "YouTube",
            "url" => array("youtube"),
            "php" => "youtube",
            "sessions_vars" => array(),
            "needConnection" => false,
            "css" => array("youtube"),
            "js" => array(),
            "isPhp" => true,
            "icon" => "YoutubeLogo.svg",
        ),
        array(
            "name" => "dispatch",
            "title" => "Dispatch",
            "url" => array("dispatch"),
            "php" => "dispatch",
            "sessions_vars" => array(),
            "needConnection" => true,
            "css" => array("dispatch"),
            "js" => array("dispatch"),
            "isPhp" => true,
            "connectionPage" => "dispatchConnection",
            "icon" => "emergencyLogo.png",
        ),
        array(
            "name" => "dispatchConnection",
            "title" => "Dispatch - Connexion",
            "url" => array("dispatch"),
            "php" => "dispatchConnection",
            "sessions_vars" => array(),
            "needConnection" => false,
            "css" => array("dispatchConnection"),
            "js" => array("connection"),
            "isPhp" => true,
            "icon" => "emergencyLogo.png",
        ),
        array(
            "name" => "cv",
            "title" => "myCv",
            "url" => array("cv"),
            "php" => "myCv",
            "sessions_vars" => array(),
            "needConnection" => false,
            "css" => array("mainPage", "resumePrinting"),
            "js" => array("mainPage"),
            "isPhp" => true,
            "translateTitle" => true,
            "icon" => "BDLogo.png",
        ),
    );

    private static $redirects = array(
        array(
            "name" => "Home",
            "url" => array(""),
            "replace" => "home",
        ),
        array(
            "name" => "Connexion",
            "url" => array("home", "connexion"),
            "replace" => "connexion",
        ),
        array(
            "name" => "Connexion",
            "url" => array("home", "connection"),
            "replace" => "connection",
        ),
    );

    private static $mainUrl = "http://localhost/portfolio";

    public static function getMainUrl():string {
        return self::$mainUrl;
    }

    private static function getPageFronUrl(string $urlStr) {
        global $hlp, $trans;
        self::$pages[0]['title'] = $trans->getlanguage("pageNotFound", "Page not found");
        $url = explode("/", $urlStr);

        $found = -1;
        /**
         * Voir si la page existe et lui initier son index dans le tableau des pages
         */
        foreach (self::$pages as $pageId => $page) {
            if (count($page['url']) == count($url)) {
                $count = 0;
                foreach ($page['url'] as $i => $pageUrl) {
                    if ($pageUrl != $url[$i]) {
                        break;
                    } else {
                        $count++;
                    }
                }
                if ($count == count($url)) {
                    $found = $pageId;
                    break;
                }
            }
        }
        /**
         * Si la page n'est pas trouv??, v??rifier les redirections d'url interne au site
         */
        if ($found == -1) {
            foreach (self::$redirects as $i => $redirect) {
                if (count($redirect['url']) == count($url)) {
                    $count = 0;
                    foreach ($redirect['url'] as $x => $redirectUrl) {
                        if ($redirectUrl != $url[$count]) {
                            break;
                        } else {
                            $count++;
                        }
                    }
                    if ($count == count($url)) {
                        return self::getPageFronUrl($redirect['replace']);
                    }
                }
            }
            /**
             * Si la page de redirection n'est pas trouv??, affich?? la page NotFound erreur 404
             */
            if ($found == -1) {
                $found = self::$PAGE_NOT_FOUND_INDEX;
            }
        }
        /**
         * Suppression des valeurs des variables de session
         */
        unset($_SESSION['cssPage']);
        unset($_SESSION['jsPage']);
        unset($_SESSION['pageIcon']);
        /**
         * V??rification si la page ?? besoin d'un connexion, si l'utilisateur est bien connect??.
         * /!\ Si le site n'a pas besoin de connexion, on peut supprimer la condition juste en dessous /!\
         */
        if (self::$pages[$found]['needConnection'] == true && $hlp->isConnected() == false) {
            if (isset(self::$pages[$found]['connectionPage'])) {
                $tmp = self::$PAGE_CONNECT_INDEX;
                foreach (self::$pages as $pi => $pageConnect) {
                    if ($pageConnect['name'] == self::$pages[$found]['connectionPage']) {
                        $found = $pi;
                        break;
                    }
                }
            } else {
                $found = self::$PAGE_CONNECT_INDEX;
            }
            $_SESSION['connexionRedirect'] = $urlStr;
        }
        /**
         * Remplir les variables de session pour le langage, le css, le js, ls titre de la page
         */
        $_SESSION['titlePage'] = self::$pages[$found]['title'];
        if (isset(self::$pages[$found]['css']) && count(self::$pages[$found]['css']) > 0) {
            $tmpArr = self::$pages[$found]['css'];
            foreach ($tmpArr as $i => $tmp) {
                $tmpArr[$i] = self::$pathes['css'] . $tmp . ".css";
            }
            $_SESSION['cssPage'] = $tmpArr;
        }
        if (isset(self::$pages[$found]['icon']) && self::$pages[$found]['icon'] != "") {
            $_SESSION['pageIcon'] = "public/ressources/" . self::$pages[$found]['icon'];
        }
        if (isset(self::$pages[$found]['js']) && count(self::$pages[$found]['js']) > 0) {
            $tmpArr = self::$pages[$found]['js'];
            foreach ($tmpArr as $i => $tmp) {
                $tmpArr[$i] = self::$pathes['js'] . $tmp . ".js";
            }
            $_SESSION['jsPage'] = $tmpArr;
        }
        if (isset(self::$pages[$found]['lang'])) {
            $_SESSION['lang'] = self::$pages[$found]['lang'];
        }
        if (!isset($_SESSION['lang'])) {
            $_SESSION['lang'] = "en";
        }
        if (isset(self::$pages[$found]['translateTitle']) && self::$pages[$found]['translateTitle'] == true) {
            $_SESSION['titlePage'] = $trans->getlanguage(self::$pages[$found]['title'], "My Title");
        }
        if (self::$pages[$found]['isPhp'] == true) {
            require self::$pathes['php'] . self::$pages[$found]['php'] . ".php";
        } else {
            require self::$pathes['php'] . self::$pages[$found]['php'] . ".html";
        }
        $_SESSION['totalyPath'] = self::getMainUrl() . "/";
        $_SESSION['totalyPath'] .= self::$pages[$found]['url'][0];
        foreach (self::$pages[$found]['url'] as $i => $url) {
            if ($i > 0) {
                $_SESSION['totalyPath'] .= "/" . self::$pages[$found]['url'][$i];
            }
        }
    }

    public static function getPage() {
        $urlStr = "";
        if (isset($_GET['url'])) {
            $urlStr = $_GET['url'];
        }
        self::getPageFronUrl($urlStr);
    }
}
