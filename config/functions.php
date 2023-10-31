<?php
/** Повна адреса */
define("APP_URL_F", "https://localhost/tea");
/** Робоча папка */
define("APP_URL_B", "/tea");
/** Локалізація */
define("APP_LOCALE", "uk_UA");
/** Мова */
define("APP_LANG", "uk");
/** Час дії сесії за замовчуванням */
define("APP_SESSION_TIME", 3600 * 24);
/** Публічна папка assets*/
define("APP_ASSETS_FOLDER", APP_URL_F . "/app/public/assets");
/** Час дії сесії, якщо користувач вказав "Запам'ятати" */
define("APP_SESSION_REMEMBER_TIME", 3600 * 24 * 360);
/** */
define("APP_UNSPLASH_KEY", include("unsplash-key.php"));


/**
 * Визначення констант
 * @param string $dir визначає кореневий каталог
 * @return void
 */
function fn_define(string $dir): void
{
    /** Повний щлах до діректорії */
    define("APP_PATH", $dir);
}

/**
 * Повертає ім'я файлу локалізації
 * @param string $locale
 * @return array|string|string[]
 */
function fn_get_locale_file(string $locale)
{
    $files = scandir(APP_PATH . "/locale/{$locale}/LC_MESSAGES");
    arsort($files);
    for ($i = count($files) - 1; $i >= 2; $i--) {
        $filename = explode("-", $files[$i]);
        if ($filename[0] == $locale)
            return str_replace([".po", ".mo"], "", $files[$i]);
    }
    return "messages";
}


