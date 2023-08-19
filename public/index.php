<?php
// Підключення автозавантажувача Composer
require_once '../vendor/autoload.php';

//
require_once '../config/functions.php';

fn_define(dirname(__DIR__));

$domain = fn_get_locale_file(APP_LOCALE);

if (defined('LC_MESSAGES')) {
    setlocale(LC_MESSAGES, APP_LOCALE); // Linux
} else {
    // putenv("LC_ALL={$locale}"); // windows
    putenv("LANG=" . APP_LOCALE);
    setlocale(LC_ALL, APP_LOCALE);
}

bindtextdomain($domain, "locale");
textdomain($domain);


// Ініціалізація фреймворку
$app = new Core\App();

// Запуск фреймворку
$app->run();

