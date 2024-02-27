<?php
session_start();

define("_COLOR", "darkred");
define("_ROOT", $_SERVER["DOCUMENT_ROOT"]);
define("_APP", _ROOT . "/backend-server/applications/admin");
define("_URL", "/admin");
require_once _ROOT . "/backend-server/libs/config.php";
require_once _ROOT . "/backend-server/libs/common.lib.php";
require_once _ROOT . "/backend-server/libs/view.lib.php";
require_once _ROOT . "/backend-server/applications/router.php";
?>
