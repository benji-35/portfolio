<?php
    session_start();
    use Applications\Database;
    use Applications\Helpers;
    use Applications\Router;
    use Applications\LanguageTranslating;
    use Applications\Dispatch;
    use Applications\Discuss;

    $discuss_class_name = "Applications\LanguageTranslating";

    $autoloader_discuss = function ($discuss_class_name) {
        // on prépare le terrain : on remplace le séparteur d'espace de nom par le séparateur de répertoires du système
        $name = str_replace('\\', DIRECTORY_SEPARATOR, $discuss_class_name);
        // on construit le chemin complet du fichier à inclure :
        // il faut que l'autoloader soit toujours à la racine du site : tout part de là avec __DIR__
        $path = __DIR__ . DIRECTORY_SEPARATOR . $name . '.php';

        // on vérfie que le fichier existe et on l'inclut
        // sinon on passe la main à une autre autoloader (return false)
        if (is_file($path)) {
            include $path;
        } else {
            return false;
        }
    };
    
    $translate_class_name = "Applications\LanguageTranslating";

    $autoloader_translate = function ($translate_class_name) {
        // on prépare le terrain : on remplace le séparteur d'espace de nom par le séparateur de répertoires du système
        $name = str_replace('\\', DIRECTORY_SEPARATOR, $translate_class_name);
        // on construit le chemin complet du fichier à inclure :
        // il faut que l'autoloader soit toujours à la racine du site : tout part de là avec __DIR__
        $path = __DIR__ . DIRECTORY_SEPARATOR . $name . '.php';

        // on vérfie que le fichier existe et on l'inclut
        // sinon on passe la main à une autre autoloader (return false)
        if (is_file($path)) {
            include $path;
        } else {
            return false;
        }
    };
    
    $db_class_name = "Applications\Database";

    $autoloader_db = function ($db_class_name) {
        // on prépare le terrain : on remplace le séparteur d'espace de nom par le séparateur de répertoires du système
        $name = str_replace('\\', DIRECTORY_SEPARATOR, $db_class_name);
        // on construit le chemin complet du fichier à inclure :
        // il faut que l'autoloader soit toujours à la racine du site : tout part de là avec __DIR__
        $path = __DIR__ . DIRECTORY_SEPARATOR . $name . '.php';

        // on vérfie que le fichier existe et on l'inclut
        // sinon on passe la main à une autre autoloader (return false)
        if (is_file($path)) {
            include $path;
        } else {
            return false;
        }
    };

    $hlp_class_name = "Applications\Helpers";

    $autoloader_hlp = function ($hlp_class_name) {
        // on prépare le terrain : on remplace le séparteur d'espace de nom par le séparateur de répertoires du système
        $name = str_replace('\\', DIRECTORY_SEPARATOR, $hlp_class_name);
        // on construit le chemin complet du fichier à inclure :
        // il faut que l'autoloader soit toujours à la racine du site : tout part de là avec __DIR__
        $path = __DIR__ . DIRECTORY_SEPARATOR . $name . '.php';

        // on vérfie que le fichier existe et on l'inclut
        // sinon on passe la main à une autre autoloader (return false)
        if (is_file($path)) {
            include $path;
        } else {
            return false;
        }
    };

    $rtr_class_name = "Applications\Router";

    $autoloader_rtr = function ($rtr_class_name) {
        // on prépare le terrain : on remplace le séparteur d'espace de nom par le séparateur de répertoires du système
        $name = str_replace('\\', DIRECTORY_SEPARATOR, $rtr_class_name);
        // on construit le chemin complet du fichier à inclure :
        // il faut que l'autoloader soit toujours à la racine du site : tout part de là avec __DIR__
        $path = __DIR__ . DIRECTORY_SEPARATOR . $name . '.php';

        // on vérfie que le fichier existe et on l'inclut
        // sinon on passe la main à une autre autoloader (return false)
        if (is_file($path)) {
            include $path;
        } else {
            return false;
        }
    };

    $dispatch_class_name = "Applications\Router";

    $autoloader_dispatch = function ($dispatch_class_name) {
        // on prépare le terrain : on remplace le séparteur d'espace de nom par le séparateur de répertoires du système
        $name = str_replace('\\', DIRECTORY_SEPARATOR, $dispatch_class_name);
        // on construit le chemin complet du fichier à inclure :
        // il faut que l'autoloader soit toujours à la racine du site : tout part de là avec __DIR__
        $path = __DIR__ . DIRECTORY_SEPARATOR . $name . '.php';

        // on vérfie que le fichier existe et on l'inclut
        // sinon on passe la main à une autre autoloader (return false)
        if (is_file($path)) {
            include $path;
        } else {
            return false;
        }
    };

    spl_autoload_register($autoloader_db);
    spl_autoload_register($autoloader_hlp);
    spl_autoload_register($autoloader_rtr);
    spl_autoload_register($autoloader_translate);
    spl_autoload_register($autoloader_dispatch);
    spl_autoload_register($autoloader_discuss);

    $db = new Applications\Database();
    $db = new Database();

    $hlp = new Applications\Helpers();
    $hlp = new Helpers();

    $trans = new Applications\LanguageTranslating();
    $trans = new LanguageTranslating();

    $rtr = new Applications\Router();
    $rtr = new Router();

    $dispatch = new Applications\Dispatch();
    $dispatch = new Dispatch();

    $discuss = new Applications\Discuss();
    $discuss = new Discuss();
    
    date_default_timezone_set('Europe/Paris');
    $rtr->getPage();
?>
