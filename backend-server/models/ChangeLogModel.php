<?php
namespace common\models;

class ChangeLogModel extends Model
{
    public function __construct()
    {
        parent::__construct("web_change_log");
    }

    protected function addLogData($params = [], $sql = "", $type = "")
    {
    }

    public function setValue($value, $type = "add")
    {
        $value = parent::setValue($value, $type);
        if ($type == "add") {
            $value["log_by_ip"] = $_SERVER["REMOTE_ADDR"];
            if (!empty($_SESSION["user_type"])) {
                $value["log_by_user_type"] = $_SESSION["user_type"];
            }
            if (!empty($_SESSION["user_idx"])) {
                $value["log_by_user_idx"] = $_SESSION["user_idx"];
            }
            $value["log_reg_dt"] = _DATE_YMDHIS;
        }
        return $value;
    }
}

?>
