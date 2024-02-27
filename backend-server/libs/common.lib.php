<?php
spl_autoload_register(function ($name) {
    $name = str_replace("/", "\\", $name);
    $class_path = explode("\\", $name);
    switch ($class_path[0]) {
        case "common":
            $dirs = [
                _BACKEND_SERVER . "/models/",
                _BACKEND_SERVER . "/controllers/",
                _BACKEND_SERVER . "/libs/extend/",
            ];
            break;

        case "applications":
            $dirs = [
                _APPLICATIONS . "/controllers/",
                _APPLICATIONS . "/models/",
            ];
            break;
    }
    $class_name = $class_path[2];
    foreach ($dirs as $dir) {
        if (file_exists($dir . $class_name . ".php")) {
            require_once $dir . $class_name . ".php";
            return;
        }
    }
    echo $dir . $class_name . ".php 는 존재하지 않습니다.<br>";
    exit();
});

function debug_log($class, $method, $res, $e = "")
{
    $file = _LOG . "/debug_" . _DATE_YMD . ".txt";
    $arr["DATE"] = _DATE_YMDHIS;
    $arr["ClassName"] = $class;
    $arr["MethodName"] = $method;
    $arr["ErrorCode"] = $res;
    $arr["Exception"] = $e;
    error_log(print_r($arr, true), 3, $file);
}

function hash_password($passwd)
{
    return password_hash($passwd, PASSWORD_DEFAULT);
}

function only_number($str)
{
    $str = preg_replace("/[^0-9]*/s", "", $str);
    return $str;
}

function get_alias($col, $arr = [], $include = false)
{
    $alias = "";
    foreach ($col as $k => $v) {
        if ($include) {
            if (in_array($k, $arr)) {
                if (!empty($alias)) {
                    $alias .= ", ";
                }
                $alias .= "{$v} as $k";
            }
        } else {
            if (!in_array($k, $arr)) {
                if (!empty($alias)) {
                    $alias .= ", ";
                }
                $alias .= "{$v} as $k";
            }
        }
    }
    return $alias;
}

function check_required($required, $request)
{
    foreach ($required as $c) {
        if (empty($request[$c])) {
            return false;
        }
    }
    return true;
}

function generateRandomString($length = 10)
{
    //$characters = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $characters = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $charactersLength = strlen($characters);
    $randomString = "";

    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[mt_rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function emptyRemoveArrString($str, $de = ",")
{
    $list = explode($de, $str);
    $list = array_filter($list);
    $str = implode($de, $list);
    return $str;
}

function createNickName()
{
    $ad = file(_PUBLIC . "/adjective.txt");
    $an = file(_PUBLIC . "/animal3.txt");
    //$an = file(_PUBLIC . "/name.txt");
    $str = "";
    $idx = rand(0, count($ad));
    $str .= $ad[$idx];
    $str .= " ";
    $idx = rand(0, count($an));
    $str .= $an[$idx];
    return $str;
}

function clientUrl($url, $method = "get", $value = [], $headers = [])
{
    $ch = curl_init();
    if ($method == "get") {
        $url .= "?";
        $url .= http_build_query($value, "");
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, false);
    } elseif ($method == "post") {
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $value);
    }
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $response = curl_exec($ch);
    $intReturnCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    curl_close($ch);
    return ["response" => $response, "returnCode" => $intReturnCode];
}

?>
