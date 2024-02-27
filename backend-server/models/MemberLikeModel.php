<?php
namespace common\models;

class MemberLikeModel extends Model
{
    public function __construct()
    {
        parent::__construct("web_member_like");
        $this->alias = [
            "mb_like_idx" => "mb_like_idx",
            "mb_like_by_mb_idx" => "ifnull(mb_like_by_mb_idx,'')",
            "mb_like_store_idx" => "ifnull(mb_like_store_idx,'')",
            "mb_like_reg_dt" => "ifnull(mb_like_reg_dt,'')"
        ];
    }

    public function setValue($value, $type = "set")
    {
        $value = parent::setValue($value);
        if ($type == "add") {
            $required = ["mb_like_by_mb_idx","mb_like_store_idx"];
            foreach ($required as $k) {
                if (empty($value[$k])) {
                    return [];
                }
            }
            $value["mb_like_reg_dt"] = _DATE_YMD;
        }
        return $value;
    }

    public function add($value, $check = true)
    {
        if( empty($value["mb_like_by_mb_idx"]) || empty($value["mb_like_store_idx"]) ){
            return [
                "returnCode" => "002",
                "returnData" => "",
            ];
        }
        $search = [];
        $search['mb_like_by_mb_idx'] = $value['mb_like_by_mb_idx'];
        $search['mb_like_store_idx'] = $value['mb_like_store_idx'];
        $row = $this->select("count(mb_like_idx) as cnt", $search, false);
        if ($row["returnData"]['cnt'] > 0 ) {
            return ["returnCode"=>"003","returnData"=>""];
        }
        return $this->insert($value, $check);
    }

}

?>
