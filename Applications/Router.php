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
            "css" => "pageNotFound",
            "js" => "",
            "isPhp" => true,
        ),
        array(
            "name" => "connection",
            "title" => "Connection",
            "url" => array("connection"),
            "php" => "connection",
            "sessions_vars" => array(),
            "needConnection" => true,
            "css" => "connection",
            "js" => "connection",
            "isPhp" => true,
        ),
        array(
            "name" => "googleSearch",
            "title" => "Google [copie]",
            "url" => array("google"),
            "lang" => "fr",
            "php" => "googleSearch",
            "sessions_vars" => array(),
            "needConnection" => false,
            "css" => "googleSearch",
            "js" => "googleSearch",
            "isPhp" => true,
        ),
        array(
            "name" => "main",
            "title" => "Portfolio",
            "url" => array(""),
            "php" => "mainPage",
            "sessions_vars" => array(),
            "needConnection" => false,
            "css" => "mainPage",
            "js" => "mainPage",
            "isPhp" => true,
        ),
        array(
            "name" => "mainHide",
            "title" => "Hide",
            "url" => array("hide"),
            "php" => "mainHide",
            "sessions_vars" => array(),
            "needConnection" => false,
            "css" => "mainHide",
            "js" => "mainHide",
            "isPhp" => true,
        ),
        array(
            "name" => "myCopies",
            "title" => "My Copies",
            "url" => array("copies"),
            "php" => "myCopies",
            "sessions_vars" => array(),
            "needConnection" => false,
            "css" => "myCopies",
            "js" => "",
            "isPhp" => true,
        ),
        array(
            "name" => "discuss",
            "title" => "myDiscussionTitle",
            "url" => array("discuss"),
            "php" => "discuss",
            "sessions_vars" => array(),
            "needConnection" => true,
            "css" => "discuss",
            "js" => "",
            "isPhp" => true,
            "translateTitle" => true,
        ),
        array(
            "name" => "youtube",
            "title" => "YouTube",
            "url" => array("youtube"),
            "php" => "youtube",
            "sessions_vars" => array(),
            "needConnection" => false,
            "css" => "youtube",
            "js" => "",
            "isPhp" => true,
        ),
        array(
            "name" => "dispatch",
            "title" => "Dispatch",
            "url" => array("dispatch"),
            "php" => "dispatch",
            "sessions_vars" => array(),
            "needConnection" => true,
            "css" => "dispatch",
            "js" => "dispatch",
            "isPhp" => true,
            "connectionPage" => "dispatchConnection",
        ),
        array(
            "name" => "dispatchConnection",
            "title" => "Dispatch - Connexion",
            "url" => array("dispatch"),
            "php" => "dispatchConnection",
            "sessions_vars" => array(),
            "needConnection" => false,
            "css" => "dispatchConnection",
            "js" => "connection",
            "isPhp" => true,
        ),
        array(
            "name" => "cv",
            "title" => "myCv",
            "url" => array("cv"),
            "php" => "myCv",
            "sessions_vars" => array(),
            "needConnection" => false,
            "css" => "mainPage",
            "js" => "mainPage",
            "isPhp" => true,
            "translateTitle" => true,
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
         * Si la page n'est pas trouvé, vérifier les redirections d'url interne au site
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
             * Si la page de redirection n'est pas trouvé, affiché la page NotFound erreur 404
             */
            if ($found == -1) {
                $found = self::$PAGE_NOT_FOUND_INDEX;
            }
        }
        /**
         * Suppression des valeurs des variables de session
         */
        unset($_SESSION['connexionRedirect']);
        unset($_SESSION['cssPage']);
        unset($_SESSION['jsPage']);
        /**
         * Vérification si la page à besoin d'un connexion, si l'utilisateur est bien connecté.
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
        if (isset(self::$pages[$found]['css']) && self::$pages[$found]['css'] != "") {
            if (isset(self::$pages[$found]['isScss']) && self::$pages[$found]['isScss'] == true) {
                $_SESSION['cssPage'] = self::$pathes['css'] . self::$pages[$found]['css'] . ".scss";
            } else {
                $_SESSION['cssPage'] = self::$pathes['css'] . self::$pages[$found]['css'] . ".css";
            }
        }
        if (isset(self::$pages[$found]['js']) && self::$pages[$found]['js'] != "") {
            $_SESSION['jsPage'] = self::$pathes['js'] . self::$pages[$found]['js'] . ".js";
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
