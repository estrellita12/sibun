<?php
namespace applications\models;

class BtnGroupModel extends \common\models\BtnGroupModel
{
    public function __construct()
    {
        parent::__construct();
        $this->alias = [
            "btn_grp_idx" => "btn_grp_idx",
            "btn_grp_by_mb_idx" => "btn_grp_by_mb_idx",
            "btn_grp_title" => "ifnull(btn_grp_title,'')",
            "btn_grp_color" => "ifnull(btn_grp_color,'')",
            "btn_grp_cycle" => "ifnull(btn_grp_cycle,0)",
            "btn_grp_orderby" => "ifnull(btn_grp_orderby,0)",
            "btn_grp_use_yn" => "ifnull(btn_grp_use_yn,'')",
            "btn_grp_reg_dt" => "ifnull(btn_grp_reg_dt,'')",
            "btn_grp_update_dt" => "ifnull(btn_grp_update_dt,'')",
        ];
    }

    public function getSearch($request = [])
    {
        $search = [];
        $search["col"] = "btn_grp_orderby";
        $search["colby"] = "asc";
        $search["btn_grp_use_yn"] = "y";

        if (!empty($request["rpp"])) {
            $search["rpp"] = $request["rpp"];
        }
        if (!empty($request["page"])) {
            $search["page"] = $request["page"];
        }
        return $search;
    }
}
?>
