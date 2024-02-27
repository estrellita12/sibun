<?php
namespace common\models;

class QaModel extends Model
{
    public function __construct()
    {
        parent::__construct("web_qa");
    }

    public function setValue($value, $type = "set")
    {
        $value = parent::setValue($value);
        if ($type == "add") {
            $required = ["qa_content"];
            if (!check_required($required, $value)) {
                return [];
            }
            $value["qa_reg_dt"] = _DATE_YMDHIS;
        }
        $value["qa_update_dt"] = _DATE_YMDHIS;
        if ($type == "set") {
            unset($value["qa_idx"]);
        }
        return $value;
    }
}

?>
