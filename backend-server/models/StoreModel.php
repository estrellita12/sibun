<?php
namespace common\models;

class StoreModel extends Model
{
    public function __construct()
    {
        parent::__construct("web_store");
        $this->alias = [
            "store_idx" => "store_idx",
            "store_pt_idx" => "store_pt_idx",
            "store_ctg_idx" => "ifnull(store_ctg_idx,'')",
            "store_stt" => "ifnull(store_stt,'0')",
            "store_main_simg" => "ifnull(store_main_simg,'')",
            "store_name" => "ifnull(store_name,'')",
            "store_tel" => "ifnull(store_tel,'')",
            "store_addr_sep1" => "ifnull(store_addr_sep1,'')",
            "store_addr_sep2" => "ifnull(store_addr_sep2,'')",
            "store_addr_sep3" => "ifnull(store_addr_sep3,'')",
            "store_addr_zip" => "ifnull(store_addr_zip,'')",
            "store_addr1" => "ifnull(store_addr1,'')",
            "store_addr2" => "ifnull(store_addr2,'')",
            "store_addr" => "ifnull(store_addr,'')",
            "store_addr_x" => "ifnull(store_addr_x,'')",
            "store_addr_y" => "ifnull(store_addr_y,'')",
            "store_open_time" => "ifnull(store_open_time,18)",
            "store_close_time" => "ifnull(store_close_time,44)",
            "store_closed_days" => "ifnull(store_closed_days,'')",
            "store_closed_notice" => "ifnull(store_closed_notice,'')",
            "store_simg_cnt" => "ifnull(store_simg_cnt,1)",
            "store_room_cnt" => "ifnull(store_room_cnt,1)",
            "store_voucher_use_yn" => "ifnull(store_voucher_use_yn,'n')",
            "store_amenities" => "ifnull(store_amenities,'')",
            "store_reg_dt" => "ifnull(store_reg_dt,'')",
            "store_update_dt" => "ifnull(store_update_dt,'')",
        ];
    }

    public function setValue($value, $type = "set")
    {
        $value = parent::setValue($value);
        if ($type == "add") {
            $required = [
                "store_pt_idx",
                "store_name",
                "store_tel",
                "store_addr",
                "store_addr_x",
                "store_addr_y",
            ];
            foreach ($required as $k) {
                if (empty($value[$k])) {
                    return [];
                }
            }
            $value["store_reg_dt"] = _DATE_YMDHIS;
        }
        $value["store_update_dt"] = _DATE_YMDHIS;

        if ($type == "set") {
            unset($value["store_idx"]);
            unset($value["store_pt_id"]);
            unset($value["store_reg_dt"]);
        }
        return $value;
    }

    public function isOwnerPartnerCheck($idx, $pt_idx)
    {
        $col = "count(store_idx) as cnt";
        $search = [];
        $search["store_idx"] = $idx;
        $search["store_pt_idx"] = $pt_idx;
        $row = $this->select($col, $search, false);
        if ($row["returnCode"] == "000" && $row["returnData"]["cnt"] > 0) {
            return true;
        } else {
            return false;
        }
    }
}

?>
