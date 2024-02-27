<?php

function unset_session($name)
{
    unset($_SESSION[$name]);
}

function set_session($session_name, $value)
{
    $_SESSION["$session_name"] = $value;
}

function get_session($session_name)
{
    if (!empty($_SESSION[$session_name])) {
        return $_SESSION["$session_name"];
    } else {
        return null;
    }
}

function get_qstr($exc = "")
{
    $exc = explode(",", $exc);
    $url = "";
    $i = 0;
    foreach ($_REQUEST as $key => $value) {
        if (in_array($key, $exc)) {
            continue;
        }
        if ($key == "url") {
            continue;
        }
        if (!empty($value)) {
            if ($i != 0) {
                $url .= "&";
            }
            if (is_array($value)) {
                foreach ($value as $v) {
                    $url .=
                        $key .
                        urlencode("[]") .
                        "=" .
                        urlencode(trim($v)) .
                        "&";
                }
            } else {
                $url .= $key . "=" . urlencode(trim($value));
            }
        }
        $i++;
    }
    return $url;
}

function get_query($exc = "")
{
    return _SCRIPT_URI . "?" . get_qstr($exc);
}

function get_request($name, $type = "str", $text = "")
{
    if (!empty($_REQUEST[$name])) {
        $val = $_REQUEST[$name];
        switch ($type) {
            case "str":
                return $val;
            case "number":
                return number_format($val);
            default:
                return $val;
        }
    } else {
        return $text;
    }
}

function get_frm_chkbox($name, $value, $selected, $text, $type = "toggle")
{
    $chk = "";
    if ($value == $selected) {
        $chk = "checked";
    }
    if ($type == "toggle") {
        $str = "<input type=\"hidden\"  name=\"$name\" id=\"$name\" value=\"$selected\">";
        $str .= "<label for=\"{$name}Chk\">";
        $str .= "<input type=\"checkbox\" name=\"{$name}Chk\" id=\"{$name}Chk\" value=\"$value\" onclick=\"chkData('$name')\" $chk>$text</label>";
    } else {
        $str = "<label for=\"{$name}\"><input type=\"checkbox\" name=\"{$name}\" id=\"{$name}\" value=\"$value\" $chk>$text</label>";
    }
    return $str;
}

function get_frm_option($value, $selected, $text = "")
{
    if (!$text) {
        $text = $value;
    }
    if ($value == $selected) {
        return "<option value=\"$value\" selected=\"selected\">$text</option>";
    } else {
        return "<option value=\"$value\">$text</option>";
    }
}

function get_frm_rpp($selected)
{
    $str = "";
    $str .= get_frm_option("10", $selected, "10줄 정렬");
    $str .= get_frm_option("30", $selected, "30줄 정렬");
    $str .= get_frm_option("50", $selected, "50줄 정렬");
    $str .= get_frm_option("100", $selected, "100줄 정렬");
    return $str;
}

function get_date_group($fr_date, $to_date, $is_last = true)
{
    $js = " onclick=\"searchDate('{$fr_date}','{$to_date}',this.value);\"";
    $frm = [];
    $frm[] =
        '<input type="button"' .
        $js .
        ' class="btn_small btn_white" value="오늘">';
    $frm[] =
        '<input type="button"' .
        $js .
        ' class="btn_small btn_white" value="일주일">';
    $frm[] =
        '<input type="button"' .
        $js .
        ' class="btn_small btn_white" value="지난달">';
    $frm[] =
        '<input type="button"' .
        $js .
        ' class="btn_small btn_white" value="이번달">';
    if ($is_last) {
        $frm[] =
            '<input type="button"' .
            $js .
            ' class="btn_small btn_white" value="전체">';
    }
    return implode("", $frm);
}

function check_time($t)
{
    $time = date("Y-m-d", strtotime($t));
    $stamp = date("Y-m-d", strtotime(_BEGIN_DATE));
    $future = date("Y-m-d", strtotime(_END_DATE));
    if ($time <= $stamp || $time >= $future) {
        return false;
    } else {
        return true;
    }
}

function get_frm_date($name, $value, $type = "time", $other = "")
{
    if ($type == "time") {
        $str = "";
        $date = "";
        $time = "";
        $checked = "checked";
        if (!empty($value) && check_time($value)) {
            $tmp = explode(" ", $value);
            $date = $tmp[0];
            $time = $tmp[1];
            $checked = "";
        }
        $str .= "<input type=\"date\" name=\"{$name}[]\" value=\"$date\" >\n";
        $str .= "<input type=\"time\" name=\"{$name}[]\" value=\"$time\" class=\"marl5\" >\n";
        $str .= "<input type=\"checkbox\" name=\"{$name}\" value=\"inif\" id=\"{$name}\" $checked> <label for=\"{$name}\">무제한
</label>";
        return $str;
    } else {
        return "<input type=\"date\" name=\"{$name}\" value=\"{$value}\" id=\"{$name}\" size=\"6\">";
    }
}

function get_frm_radio($name, $value, $selected, $text = "", $other = "")
{
    if (!$text) {
        $text = $value;
    }
    if ($value == $selected) {
        return "<label><input type=\"radio\" name=\"$name\" value=\"$value\" checked $other > $text</label>";
    } else {
        return "<label><input type=\"radio\" name=\"$name\" value=\"$value\" $other> $text</label>";
    }
}

?>
