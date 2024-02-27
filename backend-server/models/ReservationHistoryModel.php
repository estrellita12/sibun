<?php
namespace common\models;

class ReservationHistoryModel extends Model
{
    public function __construct()
    {
        parent::__construct("web_reservation_history");
    }

    public function setValue($value, $type = "set")
    {
        $value = parent::setValue($value);
        if ($type == "add") {
            $required = [];
            if (!check_required($required, $value)) {
                return [];
            }
            $value["rsvt_hty_reg_dt"] = _DATE_YMDHIS;
        }
        if ($type == "set") {
            unset($value["rsvt_hty_idx"]);
        }

        return $value;
    }
}
