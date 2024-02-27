<?php
namespace common\models;

class BannerModel extends Model
{
    public function __construct()
    {
        parent::__construct("web_banner");
        $this->alias = [
            "bn_idx" => "bn_idx",
            "bn_orderby" => "ifnull(bn_orderby,0)",
            "bn_title" => "ifnull(bn_title,'')",
            "bn_img_src" => "ifnull(bn_img_src,'')",
            "bn_link" => "ifnull(bn_link,'')",
            "bn_target" => "ifnull(bn_target,'')",
            "bn_beg_dt" => "ifnull(bn_beg_dt,'')",
            "bn_end_dt" => "ifnull(bn_end_dt,'')",
            "bn_use_yn" => "ifnull(bn_use_yn,'')",
            "bn_reg_dt" => "ifnull(bn_reg_dt,'')",
            "bn_update_dt" => "ifnull(bn_update_dt,'')",
        ];
    }

    public function setValue($value, $type = "set")
    {
        $value = parent::setValue($value);
        if ($type == "add") {
            $required = [];
            foreach ($required as $k) {
                if (empty($value[$k])) {
                    return [];
                }
            }
            if (empty($value["bn_beg_dt"])) {
                $value["bn_beg_dt"] = "2000-01-01 00:00:00";
            }
            if (empty($value["bn_end_dt"])) {
                $value["bn_end_dt"] = "3000-01-01 00:00:00";
            }
            $value["bn_reg_dt"] = _DATE_YMDHIS;
        }

        if ($type == "set") {
            unset($value["bn_idx"]);
            unset($value["bn_reg_dt"]);
        }

        $value["bn_update_dt"] = _DATE_YMDHIS;

        return $value;
    }
}

?>
