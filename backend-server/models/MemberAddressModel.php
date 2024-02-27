<?php

namespace common\models;

class MemberAddressModel extends Model
{
    public function __construct()
    {
        parent::__construct("web_member_address");
    }

    public function setValue($value, $type = "set")
    {
        $value = parent::setValue($value);
        if ($type == "add") {
            $required = ["mb_addr_by_mb_idx", "mb_addr_address"];
            if (!check_required($required, $value)) {
                return [];
            }
            $value["mb_addr_reg_dt"] = _DATE_YMDHIS;
        }
        $value["mb_addr_update_dt"] = _DATE_YMDHIS;
        if ($type == "set") {
            unset($value["mb_addr_idx"]);
            unset($value["mb_addr_by_mb_idx"]);
        }
        return $value;
    }
}

?>
