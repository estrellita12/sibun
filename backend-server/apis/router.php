<?php
$getUrl = "";
if (isset($_GET["url"])) {
    $getUrl = $_GET["url"];
    $getUrl = rtrim($getUrl, "/");
    $getUrl = ltrim($getUrl, "/");
    $getUrl = filter_var($getUrl, FILTER_SANITIZE_URL);
}
$getParams = explode("/", $getUrl);
$params["controller"] =
    isset($getParams[0]) && $getParams[0] != "" ? $getParams[0] : "main";
$params["ident"] =
    isset($getParams[1]) && $getParams[1] != "" ? $getParams[1] : null;
$params["store"] =
    isset($getParams[2]) && $getParams[2] != "" ? $getParams[2] : null;
$params["subident"] =
    isset($getParams[3]) && $getParams[3] != "" ? $getParams[3] : null;

$controllerName = "applications\\controllers\\{$params["controller"]}Controller";
if (!empty($params["store"])) {
    $controllerName = "applications\\controllers\\{$params["controller"]}{$params["store"]}Controller";
}
new $controllerName($params);
?>
