<?php
namespace common\models;

class StoreVoucherModel extends Model
{
    public function __construct()
    {
        parent::__construct("web_store_voucher");
        $this->alias = [
            "store_voucher_idx" => "store_voucher_idx",
            "store_voucher_by_store_idx" => "store_voucher_by_store_idx",
            "store_voucher_code" => "ifnull(store_voucher_code,'')",
            "store_voucher_title" => "ifnull(store_voucher_title,'')",
            "store_voucher_desc" => "ifnull(store_voucher_desc,'')",
            "store_voucher_available_room_li" =>
                "ifnull(store_voucher_available_room_li,'')",
            "store_voucher_discount_rate" =>
                "ifnull(store_voucher_discount_rate,'')",
            "store_voucher_available_days" =>
                "ifnull(store_voucher_available_days,'')",
            "store_voucher_beg_date" => "ifnull(store_voucher_beg_date,'')",
            "store_voucher_end_date" => "ifnull(store_voucher_end_date,'')",
            "store_voucher_beg_time" => "ifnull(store_voucher_beg_time,'')",
            "store_voucher_end_time" => "ifnull(store_voucher_end_time,'')",
            "store_voucher_daily_total_cnt" =>
                "ifnull(store_voucher_daily_total_cnt,'')",
            "store_voucher_orderby" => "ifnull(store_voucher_orderby,'')",
            "store_voucher_use_yn" => "ifnull(store_voucher_use_yn,'')",
            "store_voucher_reg_dt" => "ifnull(store_voucher_reg_dt,'')",
            "store_voucher_update_dt" => "ifnull(store_voucher_update_dt,'')",
        ];
    }

    public function setValue($value, $type = "set")
    {
        $value = parent::setValue($value);
        if ($type == "add") {
            $required = ["store_voucher_by_store_idx"];
            foreach ($required as $k) {
                if (empty($value[$k])) {
                    return [];
                }
            }
            $value["store_voucher_reg_dt"] = _DATE_YMDHIS;
        }

        if ($type == "set") {
            unset($value["store_voucher__idx"]);
            unset($value["store_voucher_by_store_idx"]);
            unset($value["store_voucher_reg_dt"]);
        }
        $value["store_voucher_update_dt"] = _DATE_YMDHIS;
        return $value;
    }
}
