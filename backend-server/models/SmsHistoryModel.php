<?php
namespace common\models;

class SmsHistoryModel extends Model
{
    public function __construct()
    {
        $this->table_column_list = [
            new TableColumn(
                "sms_hty_idx",
                "int",
                "번호",
                "auto_increment primary key"
            ),
            new TableColumn("test_cellphone", "varchar(255)", "전화번호"),
        ];
        parent::__construct("web_sms_history");
    }

    public function setValue($value, $type = "set")
    {
        $value = parent::setValue($value);
        if ($type == "add") {
            $required = ["sms_hty_receiver", "sms_hty_message"];
            if (!check_required($required, $value)) {
                return [];
            }
            $value["sms_hty_reg_dt"] = _DATE_YMDHIS;
        }
        if ($type == "set") {
            unset($value["sms_hty_idx"]);
        }

        if (!empty($value["sms_hty_sender"])) {
            $value["sms_hty_sender"] = only_number($value["sms_hty_sender"]);
        }
        if (!empty($value["sms_hty_receiver"])) {
            $value["sms_hty_receiver"] = only_number(
                $value["sms_hty_receiver"]
            );
        }
        return $value;
    }
}
