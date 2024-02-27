<?php
namespace applications\models;

class ChangeLogModel extends \common\models\ChangeLogModel
{
    public function __construct()
    {
        parent::__construct();
        $this->alias = [
            "log_idx" => "log_idx",
            "log_tb_nm" => "ifnull(log_tb_nm,'')",
            "log_old_value" => "ifnull(log_old_value,'')",
            "log_new_value" => "ifnull(log_new_value,'')",
            "log_by_ip" => "ifnull(log_by_ip,'')",
            "log_by_mb_idx" => "ifnull(log_by_mb_idx,'')",
            "log_reg_dt" => "ifnull(log_reg_dt,'')",
        ];
    }
}

?>
