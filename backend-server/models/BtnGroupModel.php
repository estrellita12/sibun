<?php
namespace common\models;

class BtnGroupModel extends Model
{
    public function __construct()
    {
        parent::__construct("web_btn_group");
    }

    public function setValue($value, $type = "set")
    {
        $value = parent::setValue($value);
        if ($type == "add") {
            /*
            $required = [];
            foreach ($required as $k) {
                if (empty($value[$k])) {
                    return [];
                }
            }
            */
            $value["btn_grp_reg_dt"] = _DATE_YMDHIS;
        }
        $value["btn_grp_update_dt"] = _DATE_YMDHIS;

        if ($type == "set") {
            unset($value["btn_grp_idx"]);
            unset($value["btn_grp_by_mb_idx"]);
            unset($value["btn_grp_reg_dt"]);
        }
        return $value;
    }
}

?>
