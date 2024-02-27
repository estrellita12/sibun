<?php
namespace common\models;

use PDO;

class BtnModel extends Model
{
    public function __construct()
    {
        parent::__construct("web_btn");
    }

    public function setValue($value, $type = "set")
    {
        $value = parent::setValue($value);
        if ($type == "add") {
            $required = ["btn_by_grp_id"];
            foreach ($required as $k) {
                if (empty($value[$k])) {
                    return [];
                }
            }
            $value["btn_reg_dt"] = _DATE_YMDHIS;
        }
        $value["btn_update_dt"] = _DATE_YMDHIS;

        if ($type == "set") {
            unset($value["btn_idx"]);
            unset($value["btn_by_grp_id"]);
            unset($value["btn_reg_dt"]);
        }
        return $value;
    }

    public function ownCheck($btn_idx, $mb_idx)
    {
        try {
            $model = new \common\models\BtnGroupModel();
            $sql = " select ";
            $sql .= " count(btn_idx) as cnt ";
            $sql .= " from {$this->tb_nm} a left join {$model->tb_nm} b on  a.btn_by_grp_idx = b.btn_grp_idx ";
            $sql .= "  where a.btn_idx={$btn_idx} and b.btn_grp_by_mb_idx={$mb_idx}";
            $row = $this->execute($sql);
            if ($row["cnt"] <= 0) {
                return ["returnCode" => "001", "returnData" => ""];
            }
            return ["returnCode" => "000", "returnData" => ""];
        } catch (Exception $e) {
            return ["returnCode" => "009", "returnData" => $e->getMessage()];
        }
    }
}

?>
