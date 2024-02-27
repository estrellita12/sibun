<?php
namespace common\models;

class NoticeMemberModel extends Model
{
    public function __construct()
    {
        parent::__construct("web_notice_member");
    }

    public function setValue($value, $type = "set")
    {
        $value = parent::setValue($value);
        if ($type == "add") {
            $required = ["notice_title", "notice_content"];
            if (!check_required($required, $value)) {
                return [];
            }
            $value["notice_reg_dt"] = _DATE_YMDHIS;
        }
        $value["notice_update_dt"] = _DATE_YMDHIS;
        if ($type == "set") {
            unset($value["notice_idx"]);
        }
        return $value;
    }
}

?>
