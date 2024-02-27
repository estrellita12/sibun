<?php
namespace common\models;

class PopupModel extends Model
{
    public function __construct()
    {
        parent::__construct("web_popup");
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
            $value["popup_reg_dt"] = _DATE_YMD;
        }
        $value["popup_update_dt"] = _DATE_YMD;

        if ($type == "set") {
            unset($value["popup_idx"]);
            unset($value["popup_reg_dt"]);
        }
        return $value;
    }
}

?>
