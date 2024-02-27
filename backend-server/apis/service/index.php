<?php
session_start();

$httpOrigin = isset($_SERVER["HTTP_ORIGIN"]) ? $_SERVER["HTTP_ORIGIN"] : null;
if (
    in_array($httpOrigin, ["http://211.37.174.67:3000", "http://211.37.174.67"])
) {
    header("Access-Control-Allow-Origin: {$httpOrigin}");
}
header("Access-Control-Max-Age: 86400");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Headers: authorization, client-security-token");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");

define("_ROOT", $_SERVER["DOCUMENT_ROOT"]);
define("_APP", _ROOT . "/backend-server/apis/service");
require_once _ROOT . "/backend-server/libs/config.php";
require_once _ROOT . "/backend-server/libs/common.lib.php";
require_once _ROOT . "/backend-server/apis/router.php";
?>
