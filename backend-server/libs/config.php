<?php

date_default_timezone_set("Asia/Seoul");
if (!empty(_ROOT)) {
    define("_DOCUMENT_ROOT", _ROOT);
} elseif (!empty($_SERVER["DOCUMENT_ROOT"])) {
    define("_DOCUMENT_ROOT", $_SERVER["DOCUMENT_ROOT"]);
} elseif (!empty($_SERVER["DOCUMENT_ROOT"])) {
    define("_DOCUMENT_ROOT", "");
}

if (!empty(_APP)) {
    define("_APPLICATIONS", _APP);
} else {
    define(
        "_APPLICATIONS",
        _DOCUMENT_ROOT . "/backend-server/applications/api/service"
    );
}

define("_LOG", _DOCUMENT_ROOT . "/log");
define("_PUBLIC", _DOCUMENT_ROOT . "/public");

define("_BACKEND_SERVER", _DOCUMENT_ROOT . "/backend-server");
define("_LIBS", _BACKEND_SERVER . "/libs");

if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {
    define("_HTTP", "https://");
} else {
    define("_HTTP", "http://");
}
define("_HOST", _HTTP . $_SERVER["HTTP_HOST"]);
define("_SERVER_TIME", time());
define("_DATE_YMD", date("Y-m-d", _SERVER_TIME));
define("_DATE_YMDHIS", date("Y-m-d H:i:s", _SERVER_TIME));
define("_DBTYPE", "mysql");
define("_DBHOST", "localhost");
define("_DBNAME", "sibun");
define("_DBUSER", "sibun");
define("_DBPASSWORD", "major0921!!");

//if (strpos($_SERVER["HTTP_HOST"], "dev") !== false) {
if ( $_GET["dev"]==1 ) {
    define("_DEV", true);
    error_reporting(E_ALL);
    ini_set("display_errors", "1");
} else {
    define("_DEV", false);
}

?>
