<?php
namespace common\models;

class MemberLeaveModel extends Model
{
    public function __construct()
    {
        parent::__construct("web_member_leave");
    }

    public function setValue($value, $type = "set")
    {
        $value = parent::setValue($value);
        if ($type == "add") {
            $value["mb_leave_dt"] = _DATE_YMDHIS;
        }
        return $value;
    }
}

?>
