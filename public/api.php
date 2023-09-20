<?php
// Підключення автозавантажувача Composer
require_once '../vendor/autoload.php';
//
require_once '../config/functions.php';

fn_define(dirname(__DIR__));

use Core\Services\Auth\Auth;

$type = $_GET["type"] ?? "application";
$format = $_GET["format"] ?? "json";

header("Content-Type: {$type}/{$format}");

Auth::init();
if (Auth::isLoggedIn()) {
    if (isset($_GET['calendar'])) {
        $calendar = new \Core\Services\Calendar;
        $start = $_GET['start'] ?? null;
        $end = $_GET['end'] ?? null;
        echo json_encode($calendar->get($_GET['calendar'], $_GET));
        return;
    }

    $api = new Core\Services\Api;

    if (isset($_GET['module'])){
        echo json_encode($api->module($_GET['module'], $_GET));
        return;
    }

    if (isset($_GET['method'])) {
        echo $api->parseData($_GET['method'], $_GET);
        return;
    }
}