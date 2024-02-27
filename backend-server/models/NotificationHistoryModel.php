<?php
namespace common\models;

class NotificationHistoryModel extends Model
{
    public function __construct()
    {
        parent::__construct("web_notification_history");
    }

    public function setValue($value, $type = "set")
    {
        $value = parent::setValue($value);
        if ($type == "add") {
            /*
            $required = ["noti_hty_message", "noti_hty_result"];
            if (!check_required($required, $value)) {
                return [];
            }
            */
            $value["noti_hty_reg_dt"] = _DATE_YMDHIS;
        }
        if ($type == "set") {
            unset($value["noti_hty_idx"]);
        }
        return $value;
    }
}
