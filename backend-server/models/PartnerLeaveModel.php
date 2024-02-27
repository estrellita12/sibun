<?php
namespace common\models;

class PartnerLeaveModel extends Model
{
    public function __construct()
    {
        parent::__construct("web_partner_leave");
    }

    public function setValue($value, $type = "set")
    {
        $value = parent::setValue($value);
        if ($type == "add") {
            $value["pt_leave_dt"] = _DATE_YMDHIS;
        }
        return $value;
    }
}

?>
