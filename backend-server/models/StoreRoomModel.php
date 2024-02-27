<?php
namespace common\models;

class StoreRoomModel extends Model
{
    public function __construct()
    {
        parent::__construct("web_store_room");
        $this->alias = [
            "store_room_idx" => "store_room_idx",
            "store_room_by_store_idx" => "store_room_by_store_idx",
            "store_room_name" => "ifnull(store_room_name,'')",
            "store_room_desc" => "ifnull(store_room_desc,'')",
            "store_room_orderby" => "ifnull(store_room_orderby,'')",
            "store_room_use_yn" => "ifnull(store_room_use_yn,'n')",
            "store_room_reg_dt" => "ifnull(store_room_reg_dt,'')",
            "store_room_update_dt" => "ifnull(store_room_update_dt,'')",
        ];
    }

    public function setValue($value, $type = "set")
    {
        $value = parent::setValue($value);
        if ($type == "add") {
            $required = ["store_room_by_store_idx"];
            foreach ($required as $k) {
                if (empty($value[$k])) {
                    return [];
                }
            }
            $value["store_room_reg_dt"] = _DATE_YMDHIS;
        }
        $value["store_room_update_dt"] = _DATE_YMDHIS;

        if ($type == "set") {
            unset($value["store_room_by_store_idx"]);
            unset($value["store_room_reg_dt"]);
        }
        return $value;
    }
}
