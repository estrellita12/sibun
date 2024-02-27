<?php
namespace common\models;

class CategoryModel extends Model
{
    public function __construct()
    {
        parent::__construct("web_category");
        $this->alias = [
            "ctg_idx" => "ctg_idx",
            "ctg_title" => "ifnull(ctg_title,'')",
            "ctg_icon_img" => "ifnull(ctg_icon_img,'')",
            "ctg_head_img_src" => "ifnull(ctg_head_img_src,'')",
            "ctg_tail_img_src" => "ifnull(ctg_tail_img_src,'')",
            "ctg_color_hex" => "ifnull(ctg_color_hex,'')",
            "ctg_use_yn" => "ifnull(ctg_use_yn,'')",
            "ctg_orderby" => "ifnull(ctg_orderby,'')",
            "ctg_reg_dt" => "ifnull(ctg_reg_dt,'')",
            "ctg_update_dt" => "ifnull(ctg_update_dt,'')",
        ];
    }

    public function setValue($value, $type = "set")
    {
        $value = parent::setValue($value);
        if ($type == "add") {
            /*
            $required = [];
            foreach ($required as $k) {
                if (empty($value[$k])) {
                    return [];
                }
            }
            */
            $value["ctg_reg_dt"] = _DATE_YMD;
        }
        $value["ctg_update_dt"] = _DATE_YMD;

        if ($type == "set") {
            unset($value["ctg_idx"]);
            unset($value["ctg_reg_dt"]);
        }
        return $value;
    }
}

?>
